<?php
/*
|--------------------------------------------------------------------------
| Passbook controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../models/passbook.php';
require_once __DIR__.'/../models/category.php';

class Passbook extends Controller
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
     * category model
     *
     * @var CategoryModel
     */
    protected $category;

    /**
     * pre-dispatcher
     */
    public function preDispatch()
    {
        $this->user = new UserModel($this->db, $this->config);
        $this->passbook = new PassbookModel($this->db, $this->config);
        $this->category = new CategoryModel($this->db, $this->config);
        
        parent::preDispatch();
    }

    /**
     * Search action
     */
    public function defaultAction()
    {
        $filter = isset($_POST['filter']) ? $_POST['filter'] : [];
        
        if (!empty($_GET['get_payment']) && !empty($_GET['id_payment'])) {
            echo json_encode($this->passbook->getPayment($_GET['id_payment']));
            exit();
        }

        if (isset($_POST['add_payment']) && $_POST['form_type']=='add') {
            echo json_encode($this->passbook->add($_POST));
            exit;
        }
        
        if (isset($_POST['update_payment'])) {
            echo json_encode($this->passbook->updatePayment($_POST['id_payment'], $_POST));
            exit;
        }

        if (!empty($_POST['remove_payment']) && !empty($_POST['id_payment'])) {
            echo json_encode($this->passbook->removePayment($_POST['id_payment']));
            exit;
        }

        $data['history'] = $this->passbook->getPaymentHistory($filter);
        $data['updateUrl'] = $this->getCurrentUrl();

        $this->renderView('passbook/default', $data);
    }

    /**
     * Category action
     */
    public function categoryAction()
    {
        if (!empty($_GET['get_category']) && !empty($_GET['id_category'])) {
            $result = $this->category->getCategory($_GET['id_category']);
            echo json_encode($result);
            exit();
        }

        if (!empty($_POST['add_category'])) {
            $result = $this->category->add($_POST);
            if ($result) {
                alert_push('New category is successfully added.');
            } else {
                alert_push('New category cannot be added.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }

        if (isset($_POST['update_category']) && !empty($_POST['id_category'])){   
            $result = $this->category->updateCategory($_POST, $_POST['id_category']);
            if ($result) {
                alert_push('Category is successfully updated.');
            } else {
                alert_push('Category cannot be updated.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }

        if (!empty($_GET['remove_category']) && !empty($_GET['id_category'])) {
            $result = $this->category->removeCategory($_GET['id_category']);
            if ($result) {
                alert_push('Category is successfully removed.');
            } else {
                alert_push('Category cannot be removed.', 'danger');
            }
            $this->redirect(get_url('passbook', 'category'));
        }

        $data['categories'] = $this->category->getAllCategories();
        $data['updateUrl'] = $this->getCurrentUrl();

        $this->renderView('passbook/category', $data);
    }

    /**
     * Category type
     */
    public function categoryTypes()
    {
        return [
            'payment-method' => 'Payment Method',
            'payment-category' => 'Payment Category',
        ];
    }

}