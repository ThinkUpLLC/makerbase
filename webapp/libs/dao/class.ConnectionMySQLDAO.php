<?php
class ConnectionMySQLDAO extends PDODAO {

    public function get($user_id, $object_id, $object_type) {
        $q = <<<EOD
SELECT c.* FROM connections c
WHERE c.user_id = :user_id AND c.object_id = :object_id AND c.object_type = :object_type;
EOD;
        $vars = array (
            ':user_id' => $user_id,
            ':object_id' => $object_id,
            ':object_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $connection = $this->getDataRowAsObject($ps, "Connection");
        if (!isset($connection)) {
            throw new ConnectionDoesNotExistException('Connection does not exist.');
        }
        return $connection;
    }

    public function insert(User $user, $object) {
        $type = get_class($object);
        $q = <<<EOD
INSERT IGNORE INTO connections (
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
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function delete(User $user, $object) {
        $type = get_class($object);
        $q = <<<EOD
DELETE FROM connections WHERE user_id = :user_id AND object_id = :object_id AND object_type = :object_type
EOD;
        $vars = array (
            ':user_id' => $user->id,
            ':object_id' => $object->id,
            ':object_type' => $type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
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

    public function isFollowingMaker(User $user, Maker $maker) {
        return self::isFollowing($user, $maker->id, 'Maker');
    }

    public function isFollowingProduct(User $user, Product $product) {
        return self::isFollowing($user, $product->id, 'Product');
    }

    public function isFollowingUser(User $user, User $followed_user) {
        return self::isFollowing($user, $followed_user->id, 'User');
    }

    private function isFollowing(User $user, $object_id, $object_type) {
        try {
            self::get($user->id, $object_id, $object_type);
            return true;
        } catch (ConnectionDoesNotExistException $e) {
            return false;
        }
    }
}
