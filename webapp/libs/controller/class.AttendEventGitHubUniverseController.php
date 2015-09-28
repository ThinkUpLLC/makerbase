<?php
/**
 *  if user's maker doesn't exist
 *     create maker
 *     add maker to data store
 *     add maker to search
 *     add action
 *     add connection
 *
 *  add user's maker to attendee list
 *  force cache refresh on universe page
 *  add success message
 */
class AttendEventGitHubUniverseController extends AttendEventAuthController {

    public function authControl() {
        parent::authControl();

        $maker_dao = new MakerMySQLDAO();
        if (isset($this->logged_in_user->maker_id)) {
            $users_maker = $maker_dao->getByID($this->logged_in_user->maker_id);
        } else {
            $users_maker = $this->addMaker();
        }
        $maker_dao->setIsAttendingEvent($users_maker, 'githubuni');

        // Expire cache for GitHub Universe page
        // not logged in
        self::expireCache('event-github-universe.tpl', false);
        // logged in
        self::expireCache('event-github-universe.tpl', true);
        SessionCache::put('success_message', 'You are going to GitHub Universe! <a href="/m/'.
            $users_maker->uid.'/'.$users_maker->slug.'">Update your project list.</a>');
        $this->redirect('/universe/');
    }
}
