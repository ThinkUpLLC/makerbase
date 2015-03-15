<?php
/**
 * Smarty string diff plugin
 *
 * Type:     insert<br>
 * Name:     string_diff<br>
 * Date:     August 26, 2014
 * Purpose:  Creates an HTML diff of two text strings
 * Input:    two strings
 * Example:  {insert name="string_diff" from_text="foo" to_text="bar"}
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2009-2015 Matt Jacobs
 * @author   Matt Jacobs
 * @version 1.0
 */
function smarty_insert_string_diff($params, &$smarty) {
  if (empty($params['from_text']) && empty($params['to_text'])) {
    //trigger_error("Missing 'from_text' and 'to_text' parameters");
    return;
  } elseif (empty($params['from_text'])) {
    return "<ins>" . $params['to_text'] . "</ins>";
  } elseif (empty($params['to_text'])) {
    return "<del>" . $params['from_text'] . "</del>";
  }

  $opcodes = FineDiff::getDiffOpcodes($params['from_text'], $params['to_text'], $granularityStack = FineDiff::$wordGranularity);
  $diff = FineDiff::renderDiffToHTMLFromOpcodes($params['from_text'], $opcodes);

  if (isset($params['is_email']) && $params['is_email']) {
    $diff = str_replace('<ins', '<ins style="background: #e4f9e8; text-decoration: none;"', $diff);
    $diff = str_replace('<del', '<del style="background: #f8d9dd; color: #dc4154;"', $diff);
  }

  return $diff;
}
