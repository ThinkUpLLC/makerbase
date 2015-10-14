<?php

class MakerbaseSession extends Session {
    /**
     * Name for long-Session Cookie
     * @var str
     */
    const COOKIE_NAME = 'makerbase_session';

    public static function isLoggedIn() {
        if (SessionCache::isKeySet('user')) {
             return true;
        }
        if (!empty($_COOKIE[self::COOKIE_NAME])) {
            $cookie_dao = new CookieMySQLDAO();
            $user_uid = $cookie_dao->getUIDByCookie($_COOKIE[self::COOKIE_NAME]);
            if ($user_uid) {
                $user_dao = new UserMySQLDAO();
                $user = $user_dao->get($user_uid);
                if ($user) {
                    self::completeLogin($user_uid);
                    return true;
                }
            }
        }
        return false;
    }

     public static function completeLogin($user_uid) {
        parent::completeLogin($user_uid);

        // check for and validate an existing long-term cookie before creating one
        $cookie_dao = new CookieMySQLDAO();
        $set_long_term = true;

        if (!empty($_COOKIE[self::COOKIE_NAME])) {
            $user_uid_from_cookie = $cookie_dao->getUIDByCookie($_COOKIE[self::COOKIE_NAME]);
            $set_long_term = $user_uid_from_cookie != $user_uid;
        }

        if ($set_long_term) {
            $cookie = $cookie_dao->generateForUser($user_uid);
            if (!headers_sent()) {
                setcookie(self::COOKIE_NAME, $cookie, time()+(60*60*24*365*10), '/', self::getCookieDomain());
            }
        }
     }

     public static function logout() {
        parent::logout();

        if (!empty($_COOKIE[self::COOKIE_NAME])) {
            if (!headers_sent()) {
                setcookie(self::COOKIE_NAME, '', time() - 60*60*24, '/', self::getCookieDomain());
            }
            $cookie_dao = new CookieMySQLDAO();
            $cookie_dao->deleteByCookie($_COOKIE[self::COOKIE_NAME]);
        }
    }

    /**
     * Generate a domain for setting cookies
     * @return str domain to use
     */
    public static function getCookieDomain() {
        if (empty($_SERVER['HTTP_HOST'])) {
            return false;
        }
        $parts = explode('.', $_SERVER['HTTP_HOST']);
        if (count($parts) == 1) {
            return $parts[0];
        }

        return '.'.$parts[count($parts)-2].'.'.$parts[count($parts)-1];
     }
 }