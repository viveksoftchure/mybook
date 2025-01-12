<?php
/*
|--------------------------------------------------------------------------
| Home controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../models/passbook.php';
require_once __DIR__.'/../models/category.php';

class Home extends Controller
{
    /**
     * User model
     *
     * @var StudentModel
     */
    protected $user;

    /**
     * Passbook model
     *
     * @var PassbookModel
     */
    protected $passbook;

    /**
     * category model
     *
     * @var CategoryModel
     */
    protected $category;

    /**
     * Student pre-dispatcher
     */
    public function preDispatch()
    {
        $this->user = new UserModel($this->db, $this->config);
        $this->passbook = new PassbookModel($this->db, $this->config);
        $this->category = new CategoryModel($this->db, $this->config);

        parent::preDispatch();
    }

    /**
     * Default action
     */
    public function defaultAction()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        $today_date = date('Y-m-d');
        
        $keywords = ifset($_POST,'keywords');
        $id_category = ifset($_POST,'id_category');
        $payment_method = ifset($_REQUEST,'payment_method');
        $activePage = ifset($_POST, 'page');
        
        $pagination = 100;
        $data['results'] = $this->passbook->getSearch($keywords, $id_category, $payment_method, $pagination, $activePage);

        if(isset($data['results']['totalRows'])) {
            $countRows = $data['results']['totalRows'];
            $data['results']= $data['results']['data'];
            $pages = ceil($countRows / $pagination);
            $data['totalResult'] = $countRows;
            $data['pageCount'] = $pages;
        }

        $data['this_month_income'] = $this->passbook->getIncomeCount('month');
        $data['this_month_expense'] = $this->passbook->getExpenseCount('month');
        $data['this_month_saving'] = $this->passbook->getSavingsCount('month');
        $data['income_increment'] = $this->passbook->getIncomeIncrement('month');
        $data['expense_increment'] = $this->passbook->getExpenseIncrement('month');
        $data['saving_increment'] = $this->passbook->getSavingIncrement('month');

        $data['user'] = $this->user->getUser($id_user);
        $data['updateUrl'] = $this->getCurrentUrl();

        $this->renderView('dashboard/home', $data);
    }
}