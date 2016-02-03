<?php

class APIController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setUpJSONResponse();

        if ($this->shouldRefreshCache() ) {
            $user_dao = new UserMySQLDAO();
            if (isset($_GET['endpoint']) && $_GET['endpoint'] == 'signups') {
                $start = (isset($_GET['start']))?date('Y-m-d', strtotime($_GET['start'])):date('Y-m-d');
                $end = (isset($_GET['end']))?date('Y-m-d', strtotime($_GET['end'])):date('Y-m-d');
                $total_signups = $user_dao->getTotalSignups($start, $end);
                $total_signups[0]["value"] = intval($total_signups[0]["value"]);
                $json_data = $total_signups[0];
            } else {
                $json_data = array('value'=>-1);
            }
            $this->setJsonData($json_data);
        }

        return $this->generateView();
    }
}
