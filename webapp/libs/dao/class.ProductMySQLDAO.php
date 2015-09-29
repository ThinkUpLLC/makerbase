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

    public function getTrendingProducts($in_last_x_days = 3, $limit = 4, $makers_per_product = 4) {
        // Don't include sponsors
        $sponsors = Config::getInstance()->getValue('sponsors');
        $exclude_sponsors = '';
        foreach ($sponsors as $key=>$val) {
            $exclude_sponsors .= " p.uid != '".$val['uid']."' AND ";
        }

        $q = <<<EOD
SELECT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name,
count(*) as total_edits FROM action_objects ao
INNER JOIN actions a ON a.id = ao.action_id
INNER JOIN products p ON ao.object_id = p.id
INNER JOIN roles r ON r.product_id = p.id
LEFT JOIN makers m ON r.maker_id = m.id
WHERE DATE(a.time_performed) > DATE_SUB(NOW(), INTERVAL $in_last_x_days DAY) AND
$exclude_sponsors
ao.object_type = 'Product' AND p.is_archived = 0
GROUP BY ao.object_id, ao.object_type
ORDER BY total_edits DESC
LIMIT :limit
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $vars = array (
            ':limit' => ($limit * $makers_per_product)
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $products = array();
        $current_product = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_product) || $current_product->uid !== $row['product_uid']) {
                if (isset($current_product)) {
                    $products[] = $current_product;
                }
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                $current_product = new Product(array_merge($product_vals, $defaults));
                $current_product->makers = array();
            }
            if (isset($row['maker_uid'])) {
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                if (count($current_product->makers) < $makers_per_product) {
                    $current_product->makers[] = new  Maker(array_merge($maker_vals, $defaults));
                }
            }
        }
        $products[] = $current_product;
        return array_slice($products, 0, $limit);
    }

    public function getNewestProducts($limit = 4, $makers_per_product = 4) {
        $q = <<<EOD
SELECT DISTINCT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name
FROM products p INNER JOIN roles r ON r.product_id = p.id
LEFT JOIN makers m ON r.maker_id = m.id
WHERE p.is_archived = 0 ORDER BY p.creation_time DESC LIMIT :limit
EOD;
        $vars = array ( ':limit' => ($limit * $makers_per_product));
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $products = array();
        $current_product = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_product) || $current_product->uid !== $row['product_uid']) {
                if (isset($current_product)) {
                    $products[] = $current_product;
                }
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                $current_product = new Product(array_merge($product_vals, $defaults));
                $current_product->makers = array();
            }
            if (isset($row['maker_uid'])) {
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                if (count($current_product->makers) < $makers_per_product) {
                    $current_product->makers[] = new  Maker(array_merge($maker_vals, $defaults));
                }
            }
        }
        $products[] = $current_product;
        return array_slice($products, 0, $limit);
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
