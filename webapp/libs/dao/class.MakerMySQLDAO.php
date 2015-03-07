<?php
class MakerMySQLDAO extends PDODAO {

    public function get($slug) {
        $q = "SELECT * FROM makers WHERE slug = :slug";
        $vars = array ( ':slug' => $slug);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker '.$slug.' does not exist.');
        }
        return $maker;
    }

    public function listMakers($limit = 50) {
        $q = "SELECT * FROM makers ORDER BY creation_time DESC LIMIT :limit;";
        $vars = array ( ':limit' => $limit);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }

    public function insert(Maker $maker) {
        $q = <<<EOD
INSERT INTO makers (
slug, username, name, url, avatar_url
) VALUES (
:slug, :username, :name, :url, :avatar_url
)
EOD;
        $vars = array (
            ':slug' => $maker->slug,
            ':username' => $maker->username,
            ':name' => $maker->name,
            ':url' => $maker->url,
            ':avatar_url' => $maker->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        try {
            $ps = $this->execute($q, $vars);
            return $this->getInsertId($ps);
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'slug') !== false) {
                $maker = $this->get($maker->slug);
                return $maker->id;
            } else {
                throw new PDOException($message);
            }
        }
    }
}
