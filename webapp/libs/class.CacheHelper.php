<?php

class CacheHelper {
    /**
     *
     * @var string cache key separator
     */
    const KEY_SEPARATOR='-';

    public static function expireCache($type, $uid, $slug) {
        if ($type == 'product') {
            $view_tpl = 'product.tpl';
        } elseif ($type == 'maker') {
            $view_tpl = 'maker.tpl';
        }

        $view_mgr = new ViewManager();
        //Clear logged in cached page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, true));
        //Clear non-logged-in cache page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, $uid, $slug, false));
    }

    private static function getCacheKeyStringForReload($template, $uid, $slug, $include_logged_in_user = true) {
        $view_cache_key = array();
        array_push($view_cache_key, $uid);
        array_push($view_cache_key, $slug);
        if (Session::isLoggedIn()) {
            array_push($view_cache_key, Session::getLoggedInUser());
        }
        return '.ht'.$template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}