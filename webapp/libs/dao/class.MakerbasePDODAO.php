<?php

abstract class MakerbasePDODAO extends PDODAO {

    protected static function mergeSQLVars($sql, $vars) {
        foreach ($vars as $k => $v) {
            $sql = str_replace($k, (is_int($v))?$v:"'".$v."'", $sql);
        }
        $config = Config::getInstance();
        $prefix = $config->getValue('table_prefix');
        $gmt_offset = $config->getGMTOffset();
        $sql = str_replace('#gmt_offset#', $gmt_offset, $sql);
        $sql = str_replace('#prefix#', $prefix, $sql);
        return $sql;
    }

    protected function generateRandomString($length) {
      $random_string = '';
      for ($i = 0; $i < $length; $i++) {
        $random_string .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
      }
      return $random_string;
    }
}