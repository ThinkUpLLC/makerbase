<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty autoversion modifier plugin
 * Given a file name, i.e. /css/base.css, replace it with a string containing the file's mtime,
 * i.e. /css/base.1221534296.css.
 * Use this in concert with a RewriteRule. Thanks to StackOverflow:
 * http://stackoverflow.com/questions/118884/what-is-an-elegant-way-to-force-browsers-to-reload-cached-css-js-files
 *
 * Type:     modifier<br>
 * Name:     autoversion<br>
 * Purpose:  output autoversioned file_name based on mtime
 * {@internal {$file_name|autoversion}
 *
 * @param $file_name  The file to be loaded.  Must be an absolute path (i.e. starting with slash).
 * @return string autoversioned file
 */
function smarty_modifier_autoversion($file_name) {
    if(strpos($file_name, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file_name))
        return $file_name;

    $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file_name);
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file_name);
}
