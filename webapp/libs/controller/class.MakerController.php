<?php

class MakerController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('maker.tpl');

        if ($this->shouldRefreshCache() ) {
            $maker_dao = new MakerMySQLDAO();

            try {
                $maker = $maker_dao->get($_GET['uid']);

                $role_dao = new RoleMySQLDAO();
                $roles = $role_dao->getByMaker($maker->id);
                $this->addToView('roles', $roles);

                // Get actions
                $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;
                $limit = 10;

                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnMaker($maker, $page_number, $limit);

                if (count($actions) > $limit) {
                    array_pop($actions);
                    $this->addToView('next_page', $page_number+1);
                }
                if ($page_number > 1) {
                    $this->addToView('prev_page', $page_number-1);
                }
                $this->addToView('actions', $actions);

                //Get collaborators
                $collaborators = $role_dao->getFrequentCollaborators($maker->id, 15);
                $collaborators_with_projects = array();
                foreach ($collaborators as $collaborator) {
                    $collaborator['projects'] = $role_dao->getCommonProjects($maker->id, $collaborator['maker_id']);
                    $collaborators_with_projects[] = $collaborator;
                }
                $this->addToView('collaborators', $collaborators_with_projects);

                // Set up Tweet button
                $tweet_maker_link = '';
                $this->addToView('maker', $maker);

                $this->addToView('placeholder', Role::getRandoPlaceholder());

                if (isset($this->logged_in_user) && $this->logged_in_user->is_admin) {
                    $last_admin_activity = $action_dao->getLastAdminActivityPerformedOnMaker($maker);
                    $this->addToView('last_admin_activity', $last_admin_activity);
                }
            } catch (MakerDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
