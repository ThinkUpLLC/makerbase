<?php
class MakerMySQLDAO extends PDODAO {

    public function archive($slug) {
        return $this->setIsArchived($slug, true);
    }

    public function unarchive($slug) {
        return $this->setIsArchived($slug, false);
    }

    private function setIsArchived($slug, $is_archived) {
        $q = <<<EOD
UPDATE makers SET is_archived = :is_archived WHERE slug = :slug
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

    public function get($slug) {
        $q = "SELECT * FROM makers WHERE slug = :slug";
        $vars = array ( ':slug' => $slug);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker '.$slug.' does not exist.');
        }
        return $maker;
    }

    public function getByID($id) {
        $q = "SELECT * FROM makers WHERE id = :id";
        $vars = array ( ':id' => $id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker does not exist.');
        }
        return $maker;
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
        //echo Debugger::mergeSQLVars($q, $vars);
        try {
            $ps = $this->execute($q, $vars);
            return $this->getInsertId($ps);
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'slug') !== false) {
                throw new DuplicateMakerException($message);
            } else {
                throw new PDOException($message);
            }
        }
    }

    public function update(Maker $maker) {
        $q = <<<EOD
UPDATE makers SET username = :username, name = :name, url = :url, avatar_url = :avatar_url
WHERE slug = :slug
EOD;
        $vars = array (
            ':slug' => $maker->slug,
            ':username' => $maker->username,
            ':name' => $maker->name,
            ':url' => $maker->url,
            ':avatar_url' => $maker->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo Debugger::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }
}
