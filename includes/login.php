<?php
/*
|--------------------------------------------------------------------------
| Login controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/auth.php';

class Login extends Controller
{
    /**
     * Authentication model
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Login constructor
     */
    public function preDispatch()
    {
        $this->auth = new Auth($this->db, $this->config);
        parent::preDispatch();
    }

    /**
     * Login action
     */
    public function loginAction()
    {
        $data['validationStatus'] = true;
        if (isset($_COOKIE['theCookie']) && empty($_SESSION['connection'])) {
            $cookie = $this->getSanitized($_COOKIE['theCookie']);
            if ($this->auth->authByCookie($cookie)) {
                if (isset($_SERVER["HTTP_REFERER"])) {
                    $this->redirect($_SERVER["HTTP_REFERER"]);
                } else {
                    $this->redirect('index.php');
                }
            }
        }

        if (isset($_SESSION['connection']) && $_SESSION['connection']) {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->redirect('index.php');
            }
        }

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $this->getSanitized($_POST['login']);
            $password = $this->getSanitized($_POST['password']);
            $remember = isset($_POST['remember']);
            if ($this->auth->authByForm($login, $password, $remember)) {
                $this->redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $data['validationStatus'] = false;
            }
        }
        $this->renderView('login/login', $data, false);
    }

    /**
     * Connect action
     */
    public function connectAction()
    {
        if (!empty($_SESSION['connection']) && !empty($_POST['user_connect']) && !empty($_POST['id_user'])) {
            if ($this->auth->connectUser($_POST['id_user'])) {
                $this->redirect('index.php');
            }
        }
        $this->redirect($this->getUrl($this->t1, 'login'));
    }

    /**
     * Login action
     */
    public function logoutAction()
    {
        $this->auth->logout();
    }

    /**
     * Forgot password action
     */
    public function forgotAction()
    {
        $data['validationStatus'] = null;
        if (isset($_POST['email'])) {
            $email = $this->getSanitized($_POST['email']);
            $data['validationStatus'] = $this->auth->reset($email);
        }
        $this->renderView('login/password_forgot', $data, false);
    }

    /**
     * Reset password action
     */
    public function resetAction()
    {
        $data['user'] = [];
        $data['validationStatus'] = '';

        if (isset($_GET['rs'])) {
            $reset = $this->getSanitized($_GET['rs']);
            if ($data['user'] = $this->user->getUserByReset($reset)) {
                if (isset($_POST['password']) && isset($_POST['password_confirm'])) {
                    $password = $this->getSanitized($_POST['password']);
                    $passwordConfirm = $this->getSanitized($_POST['password_confirm']);
                    if ($password == $passwordConfirm) {
                        if ($this->user->updatePassword($data['user']['id_user'], $password)) {
                            $data['validationStatus'] = 'success';
                        } else {
                            $data['validationStatus'] = 'fail';
                        }
                    }
                }
            } else {
                $data['validationStatus'] = 'invalid_code';
            }
        } else {
            $data['validationStatus'] = 'invalid_code';
        }
        $this->renderView('login/password_reset', $data, false);
    }
}