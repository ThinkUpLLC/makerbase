<?php
/**
 * This one-time script populates the users.has_added_maker, users.has_added_product, and users.has_added_role fields.
 */
//Move this to the webapp folder to run it

date_default_timezone_set('America/New_York');

require_once 'extlibs/isosceles/libs/class.Loader.php';
Loader::register(array(
'libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/',
));

class FillFirstTimeFieldsController extends MakerbaseController {

    public function control() {
        $should_keep_looping = true;
        $first_timer_dao = new FirstTimerMySQLDAO();

        while ($should_keep_looping) {
            //Get users who have has_added_maker equal to NULL
            $user_ids = $first_timer_dao->getUsersWithNullFirstTimers();
            if (count($user_ids) > 0) {
                //For each user, fill in the fields based on what's in actions table
                foreach ($user_ids as $user_id) {
                    try {
                        $result = $first_timer_dao->setFirstTimeFields($user_id['id']);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            } else {
                $should_keep_looping = false;
            }
        }
    }
}

class FirstTimerMySQLDAO extends MakerbasePDODAO {

    public function getUsersWithNullFirstTimers() {
        $q = <<<EOD
SELECT id FROM users WHERE has_added_maker IS NULL LIMIT 20;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        return $this->getDataRowsAsArrays($ps);
    }

    public function setFirstTimeFields($user_id) {
        $q = <<<EOD
SET @user_id = :user_id;

SET @has_added_maker := 0;
SELECT @has_added_maker := EXISTS(SELECT * FROM actions WHERE user_id = @user_id AND action_type = 'create' AND object_type = 'Maker');
UPDATE users SET has_added_maker = @has_added_maker WHERE id = @user_id;

SET @has_added_product := 0;
SELECT @has_added_product := EXISTS(SELECT * FROM actions WHERE user_id = @user_id AND action_type = 'create' AND object_type = 'Product');
UPDATE users SET has_added_product = @has_added_product WHERE id = @user_id;

SET @has_added_role := 0;
SELECT @has_added_role := EXISTS(SELECT * FROM actions WHERE user_id = @user_id AND action_type = 'associate');
UPDATE users SET has_added_role = @has_added_role WHERE id = @user_id;
EOD;
        $vars = array (
            ':user_id' => $user_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }
}

$controller = new FillFirstTimeFieldsController();
echo $controller->control();

