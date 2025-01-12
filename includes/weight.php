<?php
/*
|--------------------------------------------------------------------------
| Weight controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../models/weight.php';

class Weight extends Controller
{
    /**
     * User model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * Weight model
     *
     * @var WeightModel
     */
    protected $weight;

    /**
     * weight pre-dispatcher
     */
    public function preDispatch()
    {
        $this->user = new UserModel($this->db, $this->config);
        $this->weight = new WeightModel($this->db, $this->config);

        parent::preDispatch();
    }

    /**
     * Default action
     */
    public function defaultAction()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : '0';
        $today_date = date('Y-m-d');

        $from = ifset($_POST,'from', date('Y-m-01 00:00:00'));
        $to = ifset($_POST,'to', date('Y-m-t 23:59:59'));

        if (isset($_POST['add_weight']) && $_POST['weight'] != '') {
            $result = $this->weight->addWeight($_POST);
            if ($result) {
                alert_push('New weight is successfully added.');
            } else {
                alert_push('New weight cannot be added.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }
        
        $data['user'] = $this->user->getUser($id_user);
        $data['weight_data'] = $this->weight->getWeightData($from, $to);
        
        $this->renderView('weight/default', $data);
    }
}