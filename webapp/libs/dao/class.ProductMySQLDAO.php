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
}
