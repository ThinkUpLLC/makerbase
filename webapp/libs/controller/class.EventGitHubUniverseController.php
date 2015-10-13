<?php

class EventGitHubUniverseController extends MakerbaseController {
    /**
     * How many projects to display per maker.
     * @var integer
     */
    var $projects_per_maker = 5;

    public function control() {
        parent::control();
        $this->setViewTemplate('event-github-universe.tpl');

        if ($this->shouldRefreshCache() ) {
            // GitHub Universe speakers
            $maker_dao = new MakerMySQLDAO();
            $speakers_col1 = $maker_dao->getEventSpeakers('githubuni', $this->projects_per_maker, '2015-10-01');
            $speakers_col2 = $maker_dao->getEventSpeakers('githubuni', $this->projects_per_maker, '2015-10-02');

            $this->addToView('speakers_col1', $speakers_col1);
            $this->addToView('speakers_col2', $speakers_col2);

            if (MakerbaseSession::isLoggedIn() && $maker_dao->isAttendingEvent('githubuni', $this->logged_in_user) ) {
                // GitHub Universe attendees
                $makers = $maker_dao->getEventMakers('githubuni', $this->projects_per_maker);

                $halfway_mark = round(count($makers)/2);

                $makers_col1 = array_slice($makers, 0, ($halfway_mark));
                $this->addToView('makers_col1', $makers_col1);

                $makers_col2 = array_slice($makers, $halfway_mark, count($makers));
                $this->addToView('makers_col2', $makers_col2);

                // Get user's maker
                $users_maker = $maker_dao->getByID($this->logged_in_user->maker_id);
                $this->addToView('users_maker', $users_maker);
            }
        }
        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
