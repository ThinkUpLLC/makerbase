<?php

class FollowController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();

        $valid_objects = array('maker', 'user', 'project');
        $result = array();

        if (isset($_GET['object']) && in_array($_GET['object'], $valid_objects) && isset($_GET['uid'])) {
            if ($_GET['object'] == 'maker') {
                $maker_dao = new MakerMySQLDAO();
                try {
                    $maker = $maker_dao->get($_GET['uid']);

                    //Add new connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->insert($this->logged_in_user, $maker) ) {
                        $result['result'] = "Success";
                        CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);
                        CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug, 'projects');
                        CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug, 'collaborators');
                        CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug, 'inspirations');
                    } else {
                        $result['result'] = "Already following";
                    }

                    if ($maker->autofill_network == 'twitter' && isset($maker->autofill_network_id)) {
                        $user_dao = new UserMySQLDAO();
                        $user = $user_dao->getByTwitterUserId($maker->autofill_network_id);
                        if ($connection_dao->insert($this->logged_in_user, $user)) {
                            CacheHelper::expireCache('user.tpl', $user->uid, null);
                        }
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

                    //Add new connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->insert($this->logged_in_user, $product)) {
                        $result['result'] = "Success";
                        CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
                    } else {
                        $result['result'] = "Already following";
                    }
                } catch (ProductDoesNotExistException $e) {
                    $result['error'] = $e->getMessage();
                }
            } elseif ($_GET['object'] == 'user') {
                $user_dao = new UserMySQLDAO();
                try {
                    $user = $user_dao->get($_GET['uid']);

                    //Add new connection
                    $connection_dao = new ConnectionMySQLDAO();
                    if ($connection_dao->insert($this->logged_in_user, $user)) {
                        $result['result'] = "Success";
                        CacheHelper::expireCache('user.tpl', $user->uid, $user->slug);
                    } else {
                        $result['result'] = "Already following";
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