<?php
/*
|--------------------------------------------------------------------------
| User controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';

class User extends Controller
{
    /**
     * Student model
     *
     * @var StudentModel
     */
    protected $user;

    /**
     * Student pre-dispatcher
     */
    public function preDispatch()
    {
        $this->user = new UserModel($this->db, $this->config);
        
        parent::preDispatch();
    }

    /**
     * Search action
     */
    public function searchAction()
    {
        if (!empty($_POST['add_user'])) {
            $result = $this->user->add($_POST);
            if ($result) {
                alert_push('New user is successfully added.');
            } else {
                alert_push('New user cannot be added.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }

        if (isset($_POST['update_user']) && !empty($_POST['id_user'])) {
            $result = $this->user->updateUser($_POST, $_POST['id_user']);
            if ($result) {
                alert_push('User is successfully updated.');
            } else {
                alert_push('User cannot be updated.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }

        if (isset($_GET['remove_user']) && !empty($_GET['id_user'])) {
            $result = $this->user->removeUser($_GET['id_user']);
            if ($result) {
                alert_push('User is successfully removed.');
            } else {
                alert_push('User cannot be removed.', 'danger');
            }
            $this->redirect($this->getUrl($this->t1, $this->t2));
        }

        if (isset($_GET['send_invitation']) && !empty($_GET['id_user'])) {
            $result = $this->user->sendInvitation($_GET['id_user']);
            if ($result) {
                alert_push('Invitation is successfully sent to the user.');
            } else {
                alert_push('Invitation cannot be sent to the user.', 'danger');
            }
            $this->redirect($this->getUrl($this->t1, $this->t2));
        }

        if (isset($_GET['get_user']) && !empty($_GET['id_user'])) {
            // ob_clean();
            echo json_encode($this->user->getUser($_GET['id_user']));
            exit;
        }

        if (isset($_GET['check_email']) && !empty($_GET['email'])) {
            // ob_clean();
            echo json_encode($this->user->checkEmail($_GET['email'], ifset($_GET, 'id_user', 0)));
            exit;
        }

        $data['users'] = $this->user->getList();
        $data['checkEmailUrl'] = $this->getUrl($this->t1, $this->t2, 'check_email=1');
        $data['updateUrl'] = $this->getCurrentUrl();
        
        $this->renderView('user/search', $data);
    }

    /**
     * Sheet action
     */
    public function sheetAction()
    {
        $this->renderView('user/sheet');
    }
}