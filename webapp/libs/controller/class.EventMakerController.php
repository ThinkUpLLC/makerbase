<?php

class EventMakerController extends MakerbaseController {
    /**
     * How many projects to display per maker.
     * @var integer
     */
    var $projects_per_maker = 4;

    public function control() {
        parent::control();
        $this->setViewTemplate('xoxo2015.tpl');

        if ($this->shouldRefreshCache() ) {
            // XOXO 2015 makers
            $maker_dao = new MakerMySQLDAO();
            $makers = $maker_dao->getEventMakers('xoxo2015');

            // Get each maker's projects
            // @TODO Optimize this!
            $role_dao = new RoleMySQLDAO();
            foreach ($makers as $maker) {
                $roles = $role_dao->getByMaker($maker->id);
                if (count($roles) > 0 ) {
                    $maker->products = array();
                    $i = 0;
                    foreach ($roles as $role) {
                        if ($i < $this->projects_per_maker) {
                            $maker->products[] = $role->product;
                            $i++;
                        }
                    }
                }
            }

            $halfway_mark = round(count($makers)/2);

            $makers_col1 = array_slice($makers, 0, ($halfway_mark-1));
            $this->addToView('makers_col1', $makers_col1);

            $makers_col2 = array_slice($makers, $halfway_mark, $halfway_mark);
            $this->addToView('makers_col2', $makers_col2);
        }
        return $this->generateView();
    }
}
