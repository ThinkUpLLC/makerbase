<?php

class CacheHelper {
    /**
     *
     * @var string cache key separator
     */
    const KEY_SEPARATOR='-';

    public static function expireCache($view_tpl, $uid, $slug) {
        $view_mgr = new ViewManager();
        //Clear logged in cached page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, true));
        //Clear non-logged-in cache page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, false));
    }

    public static function expireLandingAndUserActivityCache($uid) {
        $view_mgr = new ViewManager();
        //Clear logged in cached page
        $view_mgr->clearCache('landing.tpl', '.htlanding.tpl-'.$uid);
        $view_mgr->clearCache('user.tpl', '.htuser.tpl-'.$uid);
        //Clear non-logged-in cache page
        $view_mgr->clearCache('landing.tpl', '.htlanding.tpl-');
        $view_mgr->clearCache('user.tpl', '.htuser.tpl-'.$uid.'-'.$uid);
    }

    private static function getCacheKeyStringForReload($template, $uid, $slug, $include_logged_in_user = true) {
        $view_cache_key = array();
        array_push($view_cache_key, $uid);
        array_push($view_cache_key, strtolower($slug));
        if ($include_logged_in_user && Session::isLoggedIn()) {
            array_push($view_cache_key, Session::getLoggedInUser());
        }
        return '.ht'.$template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}