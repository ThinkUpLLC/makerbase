<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty atnames modifier plugin
 * Type:     modifier<br>
 * Name:     atnames<br>
 * Purpose:  find atnames in text
 * {@internal {$string|atnames:'#'} }}
 *
 * @param string  $string    string to linkify
 * @param string  $link_url    url that atnames should be linked to

 * @return string atnamed string
 * @author Anil Dash <anil at thinkup dot com>
 */

function smarty_modifier_atnames($string, $link_url = '#')
{
    $regex = "/@+([a-zA-Z0-9_]+)/";
    $atnamed_string = preg_replace($regex, '<a href="' .  $link_url . '$1">$0</a>', $string);
    return($atnamed_string);

}
