<?php
class MadeWithMySQLDAO extends MakerbasePDODAO {

    public function archive($uid) {
        return $this->setIsArchived($uid, true);
    }

    public function unarchive($uid) {
        return $this->setIsArchived($uid, false);
    }

    private function setIsArchived($uid, $is_archived) {
        $q = <<<EOD
UPDATE made_withs SET is_archived = :is_archived WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid,
            ':is_archived' => $is_archived
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function insert(MadeWith $madewith) {
        $madewith->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT IGNORE INTO made_withs (
uid, product_id, used_product_id
) VALUES (
:uid, :product_id, :used_product_id
)
EOD;
        $vars = array (
            ':uid' => $madewith->uid,
            ':product_id' => $madewith->product_id,
            ':used_product_id' => $madewith->used_product_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $madewith->id = $this->getInsertId($ps);
                return $madewith;
            } catch (PDOException $e) {
                $message = $e->getMessage();
                if (strpos($message,'Duplicate entry') !== false && strpos($message,'uid') !== false) {
                    $try_insert = true;
                } else {
                    throw new PDOException($message);
                }
            }
        }
    }

    public function get($uid) {
        $q = "SELECT * FROM made_withs mw WHERE uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $madewith = $this->getDataRowAsObject($ps, 'MadeWith');
        $madewith->is_archived = PDODAO::convertDBToBool($madewith->is_archived);
        if (!isset($madewith)) {
            throw new MadeWithDoesNotExistException('MadeWith '.$uid.' does not exist.');
        }
        return $madewith;
    }

    public function getByProduct($product) {
        $q = <<<EOD
SELECT mw.*, mw.id AS mw_id, mw.uid AS mw_uid, p.*, p.id AS product_id, p.uid AS product_uid FROM made_withs mw
INNER JOIN products p ON mw.used_product_id = p.id WHERE mw.is_archived = 0 AND p.is_archived = 0
AND mw.product_id = :product_id LIMIT 3
EOD;
        $vars = array ( ':product_id' => $product->id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $madewiths = array();
        foreach ($rows as $row) {
            $madewith = new MadeWith($row);
            $madewith->id = $row['mw_id'];
            $madewith->uid = $row['mw_uid'];
            $madewith->is_archived = false;
            $used_product = new Product($row);
            $used_product->id = $row['product_id'];
            $used_product->uid = $row['product_uid'];
            $madewith->used_product = $used_product;
            $madewith->product = $product;
            $madewiths[] = $madewith;
        }
        return $madewiths;
    }
}
