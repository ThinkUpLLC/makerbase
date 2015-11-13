<?php

class MakerbaseController extends Controller {
    /**
     * @var User $logged_in_user
     */
    var $logged_in_user;

    public function control() {
        $cfg = Config::getInstance();
        $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
        $this->addToView('image_proxy_sig', $cfg->getValue('image_proxy_sig'));
        if (MakerbaseSession::isLoggedIn()) {
            $logged_in_user = MakerbaseSession::getLoggedInUser();
            $user_dao = new UserMySQLDAO();
            $user = $user_dao->get($logged_in_user);

            //Set admin status
            $admins = $cfg->getValue('admins');
            $user->is_admin = in_array($user->twitter_username, $admins);

            $this->addToView('logged_in_user', $user);
            $this->logged_in_user = $user;
        } else {
            $this->addToView('sign_in_with_twttr_link', '/twittersignin/');
        }
    }
    /**
     * Returns cache key as a string,
     * Preface every key with .ht to make resulting file "forbidden" by request thanks to Apache's default rule
     * <FilesMatch "^\.([Hh][Tt])">
     *    Order allow,deny
     *    Deny from all
     *    Satisfy All
     * </FilesMatch>
     *
     * Set to public for the sake of tests only.
     * @return str cache key
     */
    public function getCacheKeyString() {
        $view_cache_key = array();
        $keys = array_keys($_GET);
        foreach ($keys as $key) {
            array_push($view_cache_key, $_GET[$key]);
        }
        if (MakerbaseSession::isLoggedIn()) {
            array_push($view_cache_key, MakerbaseSession::getLoggedInUser());
        }
        return '.ht'.$this->view_template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }

    protected function setUserMessages() {
        $success_message = SessionCache::get('success_message');
        if (isset($success_message)) {
            SessionCache::put('success_message', null);
            $this->addSuccessMessage($success_message);
        }
        $error_message = SessionCache::get('error_message');
        if (isset($error_message)) {
            SessionCache::put('error_message', null);
            $this->addErrorMessage($error_message);
        }
        $info_message = SessionCache::get('info_message');
        if (isset($info_message)) {
            SessionCache::put('info_message', null);
            $this->addInfoMessage($info_message);
        }
    }

    protected function prepareJSON($indent = true, $stripslashes = true, $convert_numeric_strings = false) {
        parent::prepareJSON($indent, $stripslashes, $convert_numeric_strings);
    }

    protected function sendImageThruProxy($image_url, $type, $image_proxy_sig) {
        if (empty($image_url)) {
            if ($type == 'm' || $type == 'u') {
                return 'https://makerbase.co/assets/img/blank-maker.png';
            } else {
                return 'https://makerbase.co/assets/img/blank-project.png';
            }
        } else {
            if (!empty($image_proxy_sig)) {
                return 'https://makerbase.co/img.php?url='.$image_url."&t=".$type."&s=".$image_proxy_sig;
            } else {
                return $image_url;
            }
        }
    }
    /**
     * Generates web page markup
     *
     * @return str view markup
     */
    protected function generateView() {
        if ( SessionCache::isKeySet('last_page_profiled') ) {
            $last_page_profiled = SessionCache::get('last_page_profiled');
            SessionCache::unsetKey('last_page_profiled');
            return parent::generateView() . $last_page_profiled;
        } else {
            return parent::generateView();
        }
    }
}
