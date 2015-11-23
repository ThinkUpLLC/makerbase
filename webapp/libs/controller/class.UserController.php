<?php

class UserController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('user.tpl');

        // Is the logged in user looking at zer own settings?
        if (isset($this->logged_in_user) && isset($_GET['uid'])
            && $this->logged_in_user->uid == $_GET['uid']) {
            $is_user_settings = true;
        } else {
            $is_user_settings = false;
        }

        if ($this->shouldRefreshCache() || $is_user_settings) {
            $user_dao = new UserMySQLDAO();
            try {
                $user = $user_dao->get($_GET['uid']);
                $user = $this->processSettings($user, $user_dao);
                if (isset($user->maker_id)) {
                    $maker_dao = new MakerMySQLDAO();
                    $maker = $maker_dao->getById($user->maker_id);
                    $user->maker = $maker;
                }
                $this->addToView('user', $user);

                // Get actions
                $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;
                $limit = 10;
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getUserActivities($user->id, $page_number, $limit);
                if (count($actions) > $limit) {
                    array_pop($actions);
                    $this->addToView('next_page', $page_number+1);
                }
                if ($page_number > 1) {
                    $this->addToView('prev_page', $page_number-1);
                }
                $this->addToView('actions', $actions);


                if (isset($this->logged_in_user) && $this->logged_in_user->is_admin) {
                    $last_admin_activity = $action_dao->getLastAdminActivityPerformedOnUser($user);
                    $this->addToView('last_admin_activity', $last_admin_activity);
                }

                if (isset($this->logged_in_user)) {
                    $connection_dao = new ConnectionMySQLDAO();
                    $this->logged_in_user->is_following_user =
                        $connection_dao->isFollowingUser($this->logged_in_user, $user);
                    $this->addToView('logged_in_user', $this->logged_in_user);
                }

            } catch (UserDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }

    private function processSettings(User $user, UserMySQLDAO $user_dao) {
        // If logged-in user is this user, show settings
        if (isset($this->logged_in_user) &&
            $this->logged_in_user->twitter_user_id == $user->twitter_user_id) {
            // Process posted email address
            if (isset($_POST['email'])) {
                // Validate email here; show error message if not valid
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    // Save email address & new verification code
                    $user->email = $_POST['email'];
                    $user = $user_dao->updateEmail($user);

                    // Send confirmation email
                    $this->sendConfirmationEmail($user);
                } else {
                    SessionCache::put('error_message',
                        "Oops! That doesn't look like a valid email address. Please try again.");
                    // Transfer cached user messages to the view
                    $this->setUserMessages();
                }

                //Bust the cache
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
            } elseif (isset($_POST['email_subs_updated'])) {
                if (isset($_POST['maker_change_email'])) {
                    $has_changed_sub = $user_dao->subscribeToMakerChangeEmail($user);
                    $user->is_subscribed_maker_change_email = true;
                } else {
                    $has_changed_sub = $user_dao->unsubscribeFromMakerChangeEmail($user);
                    $user->is_subscribed_maker_change_email = false;
                }
                if (isset($_POST['announcements_email'])) {
                    $has_changed_sub = ( $has_changed_sub || $user_dao->subscribeToAnnouncementsEmail($user));
                    $user->is_subscribed_announcements_email = true;
                } else {
                    $has_changed_sub = ( $has_changed_sub || $user_dao->unsubscribeFromAnnouncementsEmail($user));
                    $user->is_subscribed_announcements_email = false;
                }
                if ($has_changed_sub) {
                    SessionCache::put('success_message', "Got it! Saved your new email settings.");
                }
            } else {
                if (isset($_GET['verify'])) {
                    if ($_GET['verify'] == $user->email_verification_code) {
                        // Verify email and null code
                        $user = $user_dao->verifyEmail($user);
                        SessionCache::put('success_message', "Great! Your email is all set.");
                        $this->setUserMessages();
                    } else {
                        // Set an error message; verification failed
                        SessionCache::put('error_message',
                            "Oops! That doesn't look right. Try to re-send the verification email.");
                        // Transfer cached user messages to the view
                        $this->setUserMessages();
                    }

                    //Bust the cache
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                }

                if (isset($_POST['resend'])) {
                    // Send confirmation email
                    $this->sendConfirmationEmail($user);

                    //Bust the cache
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                }
            }
            // Assign email_capture_state
            $email_capture_state = 'need_email';
            if (isset($user->email)) {
                if ($user->is_email_verified) {
                    $email_capture_state = 'confirmation_success';
                } else {
                    $email_capture_state = 'confirmation_pending';
                }
            }
            $this->addToView('email_capture_state', $email_capture_state);
        }
        return $user;
    }

    private function sendConfirmationEmail($user) {
        $activation_link = "http://makerbase.co/u/".$user->uid."?verify=".$user->email_verification_code;
        $params = array('USER_EMAIL'=>$user->email, 'ACTIVATION_LINK'=>$activation_link);
        try {
            $email_result = Mailer::mailHTMLViaMandrillTemplate($to=$user->email,
                $subject="Confirm your email address",
                $template_name = "Makerbase email verification", $template_params = $params);

            SessionCache::put('success_message',
                "Done! Check your inbox for a confirmation email.");
            $this->setUserMessages();
        } catch (Exception $e) {
            SessionCache::put('error_message',
                "Oops! Something went wrong: ".get_class($e)." - ".$e->getMessage());
            // Transfer cached user messages to the view
            $this->setUserMessages();
            $this->addToView('user', $user);
        }
    }
}
