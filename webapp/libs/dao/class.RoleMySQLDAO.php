<?php
class RoleMySQLDAO extends PDODAO {

    public function archive($slug) {
        return $this->setIsArchived($slug, true);
    }

    public function unarchive($slug) {
        return $this->setIsArchived($slug, false);
    }

    private function setIsArchived($slug, $is_archived) {
        $q = <<<EOD
UPDATE roles SET is_archived = :is_archived WHERE slug = :slug
EOD;
        $vars = array (
            ':slug' => $slug,
            ':is_archived' => ($is_archived)?1:0
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getByMaker($maker_id) {
        $q = <<<EOD
SELECT r.*, r.id AS role_id, p.*, p.id AS product_id FROM roles r
INNER JOIN products p ON r.product_id = p.id WHERE maker_id = :maker_id
EOD;
        $vars = array ( ':maker_id' => $maker_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $roles = array();
        foreach ($rows as $row) {
            $role = new Role($row);
            $role->id = $row['role_id'];
            $product = new Product($row);
            $product->id = $row['product_id'];
            $role->product = $product;
            $roles[] = $role;
        }
        return $roles;
    }

    public function get($id) {
        $q = "SELECT r.* FROM roles r WHERE id = :id";
        $vars = array ( ':id' => $id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $role = $this->getDataRowAsObject($ps, 'Role');
        if (!isset($role)) {
            throw new RoleDoesNotExistException('Role does not exist.');
        }
        return $role;
    }

    public function update(Role $role) {
        $q = "UPDATE roles SET start = :start, end = :end, role = :role WHERE id = :id";
        $vars = array (
            ':id' => (int)$role->id,
            ':start' => $role->start,
            ':end' => $role->end,
            ':role' => $role->role
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getByProduct($product_id) {
        $q = <<<EOD
SELECT r.*, r.id as role_id, m.*, m.id as maker_id FROM roles r
INNER JOIN makers m ON r.maker_id = m.id WHERE r.product_id = :product_id
EOD;
        $vars = array ( ':product_id' => $product_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $roles = array();
        foreach ($rows as $row) {
            $role = new Role($row);
            $role->id = $row['role_id'];
            $maker = new Maker($row);
            $maker->id = $row['maker_id'];
            $role->maker = $maker;
            $roles[] = $role;
        }
        return $roles;
    }

    public function insert(Role $role) {
        $q = <<<EOD
INSERT INTO roles (
maker_id, product_id, start, end, role
) VALUES (
:maker_id, :product_id, :start, :end, :role
)
EOD;
        $vars = array (
            ':maker_id' => $role->maker_id,
            ':product_id' => $role->product_id,
            ':start' => $role->start,
            ':end' => $role->end,
            ':role' => $role->role
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getInsertId($ps);
    }
}
