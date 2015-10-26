<?php

class UnfollowController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();

        $valid_objects = array('maker', 'user', 'project');
        $result = array();

        if (isset($_GET['object']) && in_array($_GET['object'], $valid_objects) && isset($_GET['uid'])) {
            if ($_GET['object'] == 'maker') {
                $maker_dao = new MakerMySQLDAO();
                try {
                    $maker = $maker_dao->get($_GET['uid']);

                    //Remove connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->delete($this->logged_in_user, $maker) ) {
                        $result['result'] = "Success";
                    } else {
                        $result['result'] = "Already unfollowing";
                    }

                    if ($maker->autofill_network == 'twitter' && isset($maker->autofill_network_user_id)) {
                        $user_dao = new UserMySQLDAO();
                        $user = $user_dao->getByTwitterId($maker->network_user_id);
                        $connection_dao->delete($this->logged_in_user, $user);
                    }
                } catch (MakerDoesNotExistException $e) {
                    $result['error'] = $e->getMessage();
                } catch (UserDoesNotExistException $e) {
                    // do nothing; user is optional
                }
            } elseif ($_GET['object'] == 'project') {
                $product_dao = new ProductMySQLDAO();
                try {
                    $product = $product_dao->get($_GET['uid']);

                    //Remove connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->delete($this->logged_in_user, $product)) {
                        $result['result'] = "Success";
                    } else {
                        $result['result'] = "Already unfollowing";
                    }
                } catch (ProductDoesNotExistException $e) {
                    $result['error'] = $e->getMessage();
                }
            } elseif ($_GET['object'] == 'user') {
                $user_dao = new UserMySQLDAO();
                try {
                    $user = $user_dao->get($_GET['uid']);

                    //Remove connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->delete($this->logged_in_user, $user)) {
                        $result['result'] = "Success";
                    } else {
                        $result['result'] = "Already unfollowing";
                    }
                } catch (UserDoesNotExistException $e) {
                    $result['error'] = $e->getMessage();
                }
            }
        } else {
            $result['error'] = "Invalid request";
        }

        if (isset($result['result'])) {
            CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
        }

        $this->setJsonData($result);
        return $this->generateView();
    }
}