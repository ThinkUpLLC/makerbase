<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Insert image with image proxy if one is available.
 *
 * Type:     insert
 * Name:     user_image
 * Date:     February 21, 2015
 * Purpose:  Returns user image URL
 * Input:    image_url, image_proxy_sig, type
 * Example:  {insert name="user_image" image_url="http://example.com/example.jpg" "image_proxy_sig"="abc" type="maker"}
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2015 Gina Trapani
 * @version 1.0
 */
function smarty_insert_user_image($params, &$smarty) {
    if (empty($params['type'])) {
        trigger_error("Missing 'type' parameter");
        return;
    } elseif (empty($params['image_url'])) {
        if ($params['type'] == 'm' || $params['type'] == 'u') {
            return 'https://makerba.se/assets/img/blank-maker.png';
        } else {
            return 'https://makerba.se/assets/img/blank-project.png';
        }
   } else {
        if (!empty($params['image_proxy_sig'])) {
            return 'https://makerba.se/img.php?url='.$params['image_url']."&t=".$params['type']."&s=".$params['image_proxy_sig'];
        } else {
            return $params['image_url'];
        }
   }
}
