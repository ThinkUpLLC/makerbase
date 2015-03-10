<?php
class ProductMySQLDAO extends PDODAO {

    public function get($slug) {
        $q = "SELECT * FROM products WHERE slug = :slug";
        $vars = array ( ':slug' => $slug);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $product = $this->getDataRowAsObject($ps, "Product");
        if (!isset($product)) {
            throw new ProductDoesNotExistException('Product '.$slug.' does not exist.');
        }
        return $product;
    }

    public function insert(Product $product) {
        $q = <<<EOD
INSERT INTO products (
slug, name, description, url, avatar_url
) VALUES (
:slug, :name, :description, :url, :avatar_url
)
EOD;
        $vars = array (
            ':slug' => $product->slug,
            ':name' => $product->name,
            ':description' => $product->description,
            ':url' => $product->url,
            ':avatar_url' => $product->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        try {
            $ps = $this->execute($q, $vars);
            return $this->getInsertId($ps);
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'slug') !== false) {
                throw new DuplicateProductException($message);
            } else {
                throw new PDOException($message);
            }
        }
    }

    public function update(Product $product) {
        $q = <<<EOD
UPDATE products SET name = :name, description = :description, url = :url, avatar_url = :avatar_url
WHERE slug = :slug
EOD;
        $vars = array (
            ':slug' => $product->slug,
            ':description' => $product->description,
            ':name' => $product->name,
            ':url' => $product->url,
            ':avatar_url' => $product->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }
}
