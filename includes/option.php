<?php
/*
|--------------------------------------------------------------------------
| Option controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../models/passbook.php';
require_once __DIR__.'/../models/option.php';

class Option extends Controller
{
    /**
     * user model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * passbook model
     *
     * @var PassbookModel
     */
    protected $passbook;

    /**
     * passbook model
     *
     * @var PassbookModel
     */
    protected $option;

    /**
     * pre-dispatcher
     */
    public function preDispatch()
    {
        $this->user = new UserModel($this->db, $this->config);
        $this->passbook = new PassbookModel($this->db, $this->config);
        $this->option = new OptionModel($this->db, $this->config);
        
        parent::preDispatch();
    }

    /**
     * export action
     */
    public function exportAction()
    {
        if (isset($_GET['export_db'])) {
            $result = $this->option->export($_POST);
            if ($result) {
                alert_push('DB is successfully exported.');
            } else {
                alert_push('DB cannot be exported.', 'danger');
            }
            $this->redirect(get_url('option', 'export'));
        }

        $data['history'] = $this->option->getExportHistory();
        $data['updateUrl'] = $this->getCurrentUrl();

        $this->renderView('options/export', $data);
    }
}