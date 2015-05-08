<?php

class UserController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->setViewTemplate('user.tpl');

        if ($this->shouldRefreshCache() ) {
            $user_dao = new UserMySQLDAO();
            try {
                $user = $user_dao->get($_GET['uid']);
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

                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                $this->addToView('image_proxy_sig', $image_proxy_sig);
            } catch (UserDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        return $this->generateView();
    }
}
