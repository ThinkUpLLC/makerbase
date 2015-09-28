<?php
class ProductMySQLDAO extends MakerbasePDODAO {

    public function archive($uid) {
        return $this->setIsArchived($uid, true);
    }

    public function unarchive($uid) {
        return $this->setIsArchived($uid, false);
    }

    private function setIsArchived($uid, $is_archived) {
        $q = <<<EOD
UPDATE products SET is_archived = :is_archived WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid,
            ':is_archived' => ($is_archived)?1:0
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function freeze($uid) {
        return $this->setIsFrozen($uid, true);
    }

    public function unfreeze($uid) {
        return $this->setIsFrozen($uid, false);
    }

    private function setIsFrozen($uid, $is_frozen) {
        $q = <<<EOD
UPDATE products SET is_frozen = :is_frozen WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid,
            ':is_frozen' => ($is_frozen)?1:0
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function delete($uid) {
        $q = <<<EOD
DELETE FROM products WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function get($uid) {
        $q = "SELECT * FROM products WHERE uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $product = $this->getDataRowAsObject($ps, "Product");
        if (!isset($product)) {
            throw new ProductDoesNotExistException('Product '.$uid.' does not exist.');
        }
        return $product;
    }

    public function getByID($id) {
        $q = "SELECT * FROM products WHERE id = :id";
        $vars = array ( ':id' => $id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $product = $this->getDataRowAsObject($ps, "Product");
        if (!isset($product)) {
            throw new ProductDoesNotExistException('Product does not exist.');
        }
        return $product;
    }

    public function insert(Product $product) {
        $product->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT INTO products (
uid, slug, name, description, url, avatar_url, autofill_network_id, autofill_network, autofill_network_username
) VALUES (
:uid, :slug, :name, :description, :url, :avatar_url, :autofill_network_id, :autofill_network, :autofill_network_username
)
EOD;
        $vars = array (
            ':uid' => $product->uid,
            ':slug' => $product->slug,
            ':name' => $product->name,
            ':description' => $product->description,
            ':url' => $product->url,
            ':avatar_url' => $product->avatar_url,
            ':autofill_network_id' => $product->autofill_network_id,
            ':autofill_network_username' => $product->autofill_network_username,
            ':autofill_network' => $product->autofill_network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $product->id = $this->getInsertId($ps);
                return $product;
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

    public function update(Product $product) {
        $q = <<<EOD
UPDATE products SET slug = :slug, name = :name, description = :description, url = :url, avatar_url = :avatar_url
WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $product->uid,
            ':slug' => $product->slug,
            ':description' => $product->description,
            ':name' => $product->name,
            ':url' => $product->url,
            ':avatar_url' => $product->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM products p
WHERE  is_archived = 0;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }

    public function getTrendingProducts($in_last_x_days = 3, $limit = 4) {
        $q = <<<EOD
SELECT p.*, count(*) as total_edits FROM action_objects ao
INNER JOIN actions a ON a.id = ao.action_id
INNER JOIN products p ON ao.object_id = p.id
WHERE DATE(a.time_performed) > DATE_SUB(NOW(), INTERVAL $in_last_x_days DAY)
AND ao.object_type = 'Product' AND p.is_archived = 0
GROUP BY ao.object_id, ao.object_type
ORDER BY total_edits DESC
LIMIT :limit
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $vars = array (
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getDataRowsAsObjects($ps, 'Maker'));
    }

    public function getNewestProducts($limit = 4) {
        $q = <<<EOD
SELECT DISTINCT p.* FROM products p INNER JOIN roles r ON r.product_id = p.id
WHERE p.is_archived = 0 ORDER BY p.creation_time DESC LIMIT :limit
EOD;
        $vars = array ( ':limit' => $limit);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Product");
    }

    public function getProductsThatAreFriends(User $user) {
        $q = <<<EOD
SELECT p.* FROM products p
INNER JOIN network_friends nf ON p.autofill_network_id = nf.friend_id
WHERE nf.user_id = :twitter_user_id AND nf.network = 'twitter' AND p.autofill_network = 'twitter'
AND p.creation_time >= :since_time
EOD;
        $vars = array (
            ':twitter_user_id' => $user->twitter_user_id,
            ':since_time' => $user->last_loaded_friends
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, 'Product');
    }
}
