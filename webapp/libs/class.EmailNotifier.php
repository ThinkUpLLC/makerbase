<?php

class EmailNotifier {
    /**
     * @var User User to send notification to
     */
    var $user_to_notify;
    /**
     * @var Maker Maker that was updated
     */
    var $maker_updated;
    /**
     * @var User Currently logged in user
     */
    var $logged_in_user;

    public function EmailNotifier(User $logged_in_user = null) {
        if ($logged_in_user !== null) {
            $this->logged_in_user = $logged_in_user;
        }
    }
    /**
     * Return whether or not a user should get a maker change email notification.
     * Returns true if:
     * * Maker has an associated Twitter user ID
     * * that Twitter user ID does not equal the logged in users (user isn't editing herself)
     * * the edited maker's associated user is subscribed to maker change email notifications
     * * the edited maker's associated user hasn't received an email notification at all, or within the last 24 hours
     *
     * @param  Maker  $maker Edited maker
     * @return bool
     */
    public function shouldSendMakerChangeEmailNotification(Maker $maker) {
        if (!isset($maker->autofill_network) && !isset($maker->autofill_network_id)) {
            //If maker object does not have all the attributes we need, reload from storage
            $maker_dao = new MakerMySQLDAO();
            $this->maker_updated = $maker_dao->get($maker->uid);
        } else {
            $this->maker_updated = $maker;
        }

        $should_send_maker_change_email = false;

        if (isset($this->maker_updated->autofill_network) && $this->maker_updated->autofill_network == 'twitter'
            && isset($this->maker_updated->autofill_network_id) // This is a maker with a Twitter autofill
            && ($this->maker_updated->autofill_network_id !== $this->logged_in_user->twitter_user_id)
            ) { //Maker is not editor

            try {
                //Get user to notify object
                $user_dao = new UserMySQLDAO();
                $user_to_notify = $user_dao->getByTwitterUserId($this->maker_updated->autofill_network_id);
                $this->user_to_notify = $user_to_notify;

                if (isset($user_to_notify->email) && $user_to_notify->is_email_verified &&
                    $user_to_notify->is_subscribed_maker_change_email) {

                    if (!isset($user_to_notify->last_maker_change_email_sent)) { //Never received a notification
                        $should_send_maker_change_email = true;
                    } else {
                        $last_sent_date = new DateTime($user_to_notify->last_maker_change_email_sent);
                        $last_sent_date_plus_24 = $last_sent_date->add(new DateInterval('P1D'));
                        $cur_date = new DateTime();
                        if ($last_sent_date_plus_24 < $cur_date) {
                            $should_send_maker_change_email = true;
                        }
                    }
                }
            } catch (UserDoesNotExistException $e) {
                //There's no user to notify, so do nothing here and just move on
            }
        }
        return $should_send_maker_change_email;
    }
    /**
     * Send email notification about a maker change.
     * @return void
     */
    public function sendMakerChangeEmailNotification() {
        if (!isset($this->logged_in_user) || !isset($this->maker_updated) || !isset($this->user_to_notify)) {
            throw new Exception('Email notifier has not bee initialized');
        }
        $maker_page_link = "http://makerba.se/m/".$this->maker_updated->uid."/".$this->maker_updated->slug;
        $ga_campaign_tags = "?utm_source=Makerbase&utm_medium=Email&utm_campaign=Update%20maker";
        $editor_name = $this->logged_in_user->twitter_username;
        $unsub_link = "http://makerba.se/u/".$this->user_to_notify->uid;
        $params = array(
            'USER_EMAIL'=>$this->user_to_notify->email,
            'MAKER_PAGE_LINK'=>$maker_page_link.$ga_campaign_tags,
            'EDITOR_NAME'=>$editor_name,
            'UNSUBSCRIBE_LINK'=>$unsub_link
        );
        try {
            $email_result = Mailer::mailHTMLViaMandrillTemplate($to=$this->user_to_notify->email,
                $subject=$editor_name." updated you on Makerbase",
                $template_name = "Makerbase page change notification", $template_params = $params);

            //Set notified user's last_email_notification_sent value here
            $user_dao = new UserMySQLDAO();
            $user_dao->updateLastMakerChangeEmailNotificationSentTime($this->user_to_notify);
        } catch (Exception $e) {
            error_log(get_class($e)." - ".$e->getMessage());
        }
    }

    /**
     * Send email notification about a new inspiration.
     * This notification counts toward a user's maker change notification balance, so if it is sent, another
     * change won't get sent for another 24 hours.
     * @return void
     */
    public function sendNewInspirationEmailNotification() {
        if (!isset($this->logged_in_user) || !isset($this->maker_updated) || !isset($this->user_to_notify)) {
            throw new Exception('Email notifier has not bee initialized');
        }
        $maker_page_link = "http://makerba.se/m/".$this->maker_updated->uid."/".$this->maker_updated->slug
            ."/inspirations";
        $ga_campaign_tags = "?utm_source=Makerbase&utm_medium=Email&utm_campaign=New%20inspiration";
        $editor_name = $this->logged_in_user->twitter_username;
        $unsub_link = "http://makerba.se/u/".$this->user_to_notify->uid;
        $params = array(
            'USER_EMAIL'=>$this->user_to_notify->email,
            'MAKER_PAGE_LINK'=>$maker_page_link.$ga_campaign_tags,
            'EDITOR_NAME'=>$editor_name,
            'UNSUBSCRIBE_LINK'=>$unsub_link
        );
        try {
            $email_result = Mailer::mailHTMLViaMandrillTemplate($to=$this->user_to_notify->email,
                $subject=$editor_name." said you're an inspiration",
                $template_name = "Makerbase inspiration notification", $template_params = $params);

            //Set notified user's last_email_notification_sent value here
            $user_dao = new UserMySQLDAO();
            $user_dao->updateLastMakerChangeEmailNotificationSentTime($this->user_to_notify);
        } catch (Exception $e) {
            error_log(get_class($e)." - ".$e->getMessage());
        }
    }
}