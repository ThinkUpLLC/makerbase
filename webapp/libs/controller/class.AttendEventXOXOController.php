<?php
/**
 * if user is on the whitelist:
 *    if user's maker doesn't exist
 *       create maker
 *       add maker to data store
 *       add maker to search
 *       add action
 *       add connection
 *
 *    add user's maker to attendee list
 *    force cache refresh on xoxo2015 page
 *    add success message
 * else
 *    add error message
 *
 */
class AttendEventXOXOController extends AttendEventAuthController {

    public function authControl() {
        parent::authControl();

        $maker_dao = new MakerMySQLDAO();
        if ($maker_dao->hasEventPermission('xoxo2015', $this->logged_in_user)) {
            if (isset($this->logged_in_user->maker_id)) {
                $users_maker = $maker_dao->getByID($this->logged_in_user->maker_id);
            } else {
                $users_maker = $this->addMaker();
            }
            $maker_dao->setIsAttendingEvent($users_maker, 'xoxo2015');

            // Expire cache for xoxo 2015 page
            // not logged in
            self::expireCache('event-xoxo2015.tpl', false);
            // logged in
            self::expireCache('event-xoxo2015.tpl', true);
            SessionCache::put('success_message', 'You are going to XOXO 2015! <a href="/m/'.
                $users_maker->uid.'/'.$users_maker->slug.'">Update your project list.</a>');
        } else {
            // Expire cache for xoxo 2015 page
            // logged in only
            self::expireCache('event-xoxo2015.tpl', true);
            SessionCache::put('error_message', 'Oops! Sorry, unable to add you to the attendee list. '.
                '<a href="mailto:team@makerba.se">Contact us</a> to get help.');
        }
        $this->redirect('/xoxo2015/');
    }
}
