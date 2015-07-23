<?php

class UserController extends MakerbaseAuthController {

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
                $this->addToView('user', $user);

                // Get actions
                $page_number = (isset($_GET['p']))?$_GET['p']:1;
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


                if ($this->logged_in_user->is_admin) {
                    $last_admin_activity = $action_dao->getLastAdminActivityPerformedOnUser($user);
                    $this->addToView('last_admin_activity', $last_admin_activity);
                }
            } catch (UserDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        return $this->generateView();
    }

    private function processSettings(User $user, UserMySQLDAO $user_dao) {
        // If logged-in user is this user, show settings
        if (isset($this->logged_in_user) &&
            $this->logged_in_user->twitter_user_id == $user->twitter_user_id) {
            // Process posted email address
            if (isset($_POST['email'])) {
                // TODO validate email here; show error message if not valid
                // Save email address & new verification code
                $user->email = $_POST['email'];
                $user = $user_dao->updateEmail($user);

                // Send confirmation email
                $this->sendConfirmationEmail($user);
            } else {
                if (isset($_GET['verify'])) {
                    if ($_GET['verify'] == $user->email_verification_code) {
                        // Verify email and null code
                        $user = $user_dao->verifyEmail($user);
                        SessionCache::put('success_message', "Great! You're all set.");
                        $this->setUserMessages();
                    } else {
                        // Set an error message; verification failed
                        SessionCache::put('error_message',
                            "Oops! That doesn't look right. Try to re-send the verification email.");
                        // Transfer cached user messages to the view
                        $this->setUserMessages();
                    }
                }

                if (isset($_POST['resend'])) {
                    // Send confirmation email
                    $this->sendConfirmationEmail($user);
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
        $activation_link = "http://makerba.se/u/".$user->uid."?verify=".$user->email_verification_code;
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
