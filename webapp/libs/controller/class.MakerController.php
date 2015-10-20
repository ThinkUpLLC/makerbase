<?php

class MakerController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('maker.tpl');

        if ($this->shouldRefreshCache() ) {
            // Get active tab (default to projects)
            $valid_tabs = array('projects', 'collaborators', 'inspirations');
            $active_tab = (isset($_GET['t']) && in_array($_GET['t'], $valid_tabs))?$_GET['t']:"projects";

            // Get page number (default to 1)
            $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;

            $maker_dao = new MakerMySQLDAO();
            try {
                $maker = $maker_dao->get($_GET['uid']);

                $role_dao = new RoleMySQLDAO();
                $action_dao = new ActionMySQLDAO();

                if ($active_tab == "projects") {
                    // Get roles
                    $roles = $role_dao->getByMaker($maker->id);
                    $this->addToView('roles', $roles);

                    $this->addToView('role_placeholder', Role::getRandoPlaceholder());

                    // Get actions
                    $limit = 10;

                    $actions = $action_dao->getActivitiesPerformedOnMaker($maker, $page_number, $limit);

                    if (count($actions) > $limit) {
                        array_pop($actions);
                        $this->addToView('next_page', $page_number+1);
                    }
                    if ($page_number > 1) {
                        $this->addToView('prev_page', $page_number-1);
                    }
                    $this->addToView('actions', $actions);

                } elseif ($active_tab == "collaborators") {
                    //Get collaborators
                    $collaborators = $role_dao->getFrequentCollaborators($maker->id, 15);
                    $collaborators_with_projects = array();
                    foreach ($collaborators as $collaborator) {
                        $collaborator['projects'] = $role_dao->getCommonProjects($maker->id, $collaborator['maker_id']);
                        $collaborators_with_projects[] = $collaborator;
                    }
                    $this->addToView('collaborators', $collaborators_with_projects);
                } elseif ($active_tab == "inspirations") {
                    // Get inspirations
                    $inspiration_dao = new InspirationMySQLDAO();
                    $inspirations = $inspiration_dao->getInspirers($maker);
                    $this->addToView('inspirations', $inspirations);

                    // Get inspired makers
                    $inspired_makers = $inspiration_dao->getInspiredMakers($maker);
                    $this->addToView('inspired_makers', $inspired_makers);

                    $this->addToView('inspiration_placeholder', Inspiration::getRandoPlaceholder());

                    if (isset($this->logged_in_user->maker_id)) {
                        $this->logged_in_user->maker = $maker_dao->getById($this->logged_in_user->maker_id);
                        $this->addToView('logged_in_user', $this->logged_in_user);
                    }
                }

                // Get user
                $maker->user = null;
                if (isset($maker->autofill_network_id) && isset($maker->autofill_network)
                    && $maker->autofill_network == 'twitter' ) {
                    $user_dao = new UserMySQLDAO();
                    try {
                        $user = $user_dao->getByTwitterUserId($maker->autofill_network_id);
                        $maker->user = $user;
                    } catch (UserDoesNotExistException $e) {
                        // No user; do nothing
                    }
                }
                $this->addToView('maker', $maker);

                $this->addToView('active_tab', $active_tab);

                if (isset($this->logged_in_user) && $this->logged_in_user->is_admin) {
                    $last_admin_activity = $action_dao->getLastAdminActivityPerformedOnMaker($maker);
                    $this->addToView('last_admin_activity', $last_admin_activity);
                }

                $is_maker_user = false;
                if (isset($this->logged_in_user)
                    && isset($maker->autofill_network_id)
                    && isset($maker->autofill_network) && $maker->autofill_network == 'twitter'
                    && $this->logged_in_user->twitter_user_id == $maker->autofill_network_id) {
                    $is_maker_user = true;
                }
                $this->addToView('is_maker_user', $is_maker_user);

            } catch (MakerDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
