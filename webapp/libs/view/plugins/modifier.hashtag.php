<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty hashtag modifier plugin
 * Type:     modifier<br>
 * Name:     hashtag<br>
 * Purpose:  find hashtags in text
 * {@internal {$string|hashtags:'#'} }}
 *
 * @param string  $string    string to linkify
 * @param string  $link_url    url that hashtag should be linked to

 * @return string hashtagged string
 * @author Anil Dash <anil at thinkup dot com>
 */

function smarty_modifier_hashtag($string, $link_url = '#')
{
    $regex = "/#+([a-zA-Z0-9_]+)/";
    $hashtagged_string = preg_replace($regex, '<a href="' .  $link_url . '$1">$0</a>', $string);
    return($hashtagged_string);

}
