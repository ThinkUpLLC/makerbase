<?php

class CacheHelper {
    /**
     *
     * @var string cache key separator
     */
    const KEY_SEPARATOR='-';

    public static function expireCache($view_tpl, $uid, $slug, $tab = null) {
        $view_mgr = new ViewManager();
        //Clear logged in cached page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, true, $tab));
        //Clear non-logged-in cache page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, false, $tab));
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

    private static function getCacheKeyStringForReload($template, $uid, $slug, $include_logged_in_user = true,
        $tab = null) {

        $view_cache_key = array();
        array_push($view_cache_key, $uid);
        array_push($view_cache_key, strtolower($slug));
        if (isset($tab)) {
            array_push($view_cache_key, strtolower($tab));
        }
        if ($include_logged_in_user && MakerbaseSession::isLoggedIn()) {
            array_push($view_cache_key, MakerbaseSession::getLoggedInUser());
        }
        return '.ht'.$template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}