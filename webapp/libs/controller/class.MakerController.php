<?php

class MakerController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->setViewTemplate('maker.tpl');

        if ($this->shouldRefreshCache() ) {
            $maker_dao = new MakerMySQLDAO();

            try {
                $maker = $maker_dao->get($_GET['uid']);
                $this->addToView('maker', $maker);

                $role_dao = new RoleMySQLDAO();
                $roles = $role_dao->getByMaker($maker->id);
                $this->addToView('roles', $roles);

                // Get actions
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnMaker($maker);
                $this->addToView('actions', $actions);

                // Transfer cached user messages to the view
                $this->setUserMessages();

                // Set up Tweet button
                $tweet_maker_link = '';
                // Get autofill
                $autofill_dao = new AutofillMySQLDAO();
                $autofill = $autofill_dao->getByMakerID($maker->id);
                if (isset($autofill)) {
                    //If autofill was from Twitter
                    if ($autofill['network'] == 'twitter') {
                        //Get user by that Twitter ID
                        $user_dao = new UserMySQLDAO();
                        try {
                            $user = $user_dao->getByTwitterUserId($autofill['network_id']);
                        } catch (UserDoesNotExistException $e) {
                            $user = null;
                        }
                        if (!isset($user)) {
                            //User doesn't exist, so add Tweet button
                            $tweet_body = " @".$autofill['network_username']." Is your Makerbase page up to date?";
                            $tweet_maker_link = ' <a href="https://twitter.com/share?text='.urlencode($tweet_body)
                                .'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,'
                                .'resizable=yes,scrollbars=yes,height=600,width=600\');return false;">Ask @'
                                .$autofill['network_username'].' to edit this page</a>';
                        }
                    }
                }
                $this->addToView('tweet_maker_link', $tweet_maker_link);

                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                $this->addToView('image_proxy_sig', $image_proxy_sig);
            } catch (MakerDoesNotExistException $e) {
                $this->addErrorMessage("Maker ".$_GET['uid']." does not exist");
            }
        }
        return $this->generateView();
    }
}
