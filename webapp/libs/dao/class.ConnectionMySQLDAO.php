<?php
class ConnectionMySQLDAO extends PDODAO {

    public function insert(User $user, $object) {
        $type = get_class($object);
        $q = <<<EOD
INSERT INTO connections (
user_id, object_id, object_type
) VALUES (
:user_id, :object_id, :object_type
)
EOD;
        $vars = array (
            ':user_id' => $user->id,
            ':object_id' => $object->id,
            ':object_type' => $type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        try {
            $ps = $this->execute($q, $vars);
            return true;
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'user_object') !== false) {
                return false;
            } else {
                throw new PDOException($message);
            }
        }
    }

    private function deleteConnectionsToObject($object_type, $object_id) {
        $q = <<<EOD
DELETE FROM connections WHERE object_id = :object_id AND object_type = :object_type;
EOD;
        $vars = array (
            ':object_id' => $object_id,
            ':object_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function deleteConnectionsToProduct($product_id) {
        return self::deleteConnectionsToObject('Product', $product_id);
    }

    public function deleteConnectionsToMaker($maker_id) {
        return self::deleteConnectionsToObject('Maker', $maker_id);
    }
}
