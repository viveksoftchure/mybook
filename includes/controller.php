<?php
/*
|--------------------------------------------------------------------------
| Abstract controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../service/layout.php';

abstract class Controller extends Layout
{
    /**
     * Route controller
     *
     * @var string
     */
    protected $t1;

    /**
     * Route controller action
     *
     * @var string
     */
    protected $t2;

    /**
     * Connection to database
     *
     * @var array
     */
    protected $db;

    /**
     * User model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * Basic constructor
     *
     * @param string $t1
     * @param string $t2
     * @param mysqli $db
     * @param array $config
     * @param array $lang
     */
    public function __construct($t1, $t2, $db, $config)
    {
        $this->t1 = $t1;
        $this->t2 = $t2;
        $this->db = $db;
        $this->config = $config;
        $this->user = new UserModel($this->db, $this->config);
    }

    /**
     * Default action
     *
     * @return void
     */
    public function defaultAction()
    {
        $this->renderView('home/home');
    }

    /**
     * Error 404 action
     *
     * @return void
     */
    public function errorAction()
    {
        $this->renderView('error/404');
    }

    /**
     * Redirect to the specified part of application
     *
     * @param string $url
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Build application URL
     *
     * @param string $t1
     * @param string $t2
     * @param string $id
     * @param string $ext
     * @return string
     */
    protected function getUrl($t1, $t2, $id = '', $ext = '')
    {
        $location = 'index.php?t1=' . $t1 . '&t2=' . $t2;
        if ($id) {
            $location .= '&' . $id;
        }
        if ($ext) {
            $location .= '&' . $ext;
        }
        return $location;
    }

    /**
     * Render view - include header, main template and footer
     *
     * @param string $template
     * @param array $data
     * @param bool $include whether include header and footer or just show a template
     * @param bool $fetch
     * @return false|string
     */
    public function renderView($template, $data = [], $include = true, $fetch = false)
    {
        $data['t1'] = $this->t1;
        $data['t2'] = $this->t2;

        return parent::renderView($template, $data, $include, $fetch);
    }

    /**
     * Allows to make some actions in controller before dispatch action
     *
     * @return void
     */
    public function preDispatch()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0 ;
        $id_audit = isset($_GET['id_audit']) ? $_GET['id_audit'] : 0 ;
        $id_auditor = isset($_GET['id_auditor']) ? $_GET['id_auditor'] : 0 ;
        $id_client = isset($_GET['id_client']) ? $_GET['id_client'] : 0 ;

        if (!empty($_POST['update_profile'])) {
            $result = $this->user->updateProfile($_SESSION['user']['id_user']);
            if ($result) {
                alert_push('User profile picture is successfully updated.');
            } else {
                alert_push('User profile picture cannot be updated.', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }

        if (!empty($_POST['update_menu'])) {
            echo json_encode($this->user->setMenuView($_SESSION['user']['id_user'], $_POST['view']));
            exit;
        }

        if (!empty($_POST['update_mode'])) {
            echo json_encode($this->user->setModeView($_SESSION['user']['id_user'], $_POST['mode']));
            exit;
        }

        if (isset($_GET['check_old_password']) && !empty($_GET['old_password'])) {
            echo json_encode($this->user->checkPassword($_GET['old_password']));
            exit;
        }

        if (!empty($_POST['update_password']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
            $password = $this->getSanitized($_POST['password']);
            $passwordConfirm = $this->getSanitized($_POST['password_confirm']);
            $result = false;

            if ($password == $passwordConfirm) {
                $result = $this->user->updatePassword($_SESSION['user']['id_user'], $password);
            }

            if ($result) {
                alert_push('Your password was updated');
            } else {
                alert_push('Your password cannot be updated', 'danger');
            }
            $this->redirect($this->getCurrentUrl());
        }
    }

    /**
     * Allows to make some actions in controller after dispatch action
     *
     * @return void
     */
    public function postDispatch()
    {
        
    }

    /**
     * Build page title
     *
     * @param string $title
     * @return string
     */
    public function getTitle($title = '')
    {
        $title = ($title) ? $title : get_class($this);
        return sprintf('%s - %s', $this->config['project_title'], $title);
    }

    /**
     * Return config app base URl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->config['url'];
    }

    /**
     * Sanitize value
     *
     * @param string $value
     * @return string
     */
    protected function getSanitized($value)
    {
        return sanitize($value);
    }

    /**
     * Prepare current url
     *
     * @return string
     */
    protected function getCurrentUrl()
    {
        $parts = [];
        foreach ($_GET as $name => $value) {
            if ($name != 't1' && $name != 't2') {
                $parts[] = $this->getSanitized($name) . '=' . $this->getSanitized($value);
            }
        }
        return $this->getUrl($this->t1, $this->t2, implode('&', $parts));
    }

    /**
     * Get current user name
     *
     * @return string
     */
    protected function getCurrentUserName()
    {
        return $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
    }

    /**
     * Get current user id
     *
     * @return int
     */
    protected function getCurrentUserId()
    {
        return $_SESSION['user']['id_user'];
    }

    /**
     * Get current user id
     *
     * @return int
     */
    protected function getCurrentUserCategory()
    {
        return $_SESSION['user']['id_category'];
    }

    /**
     * Check if current user is admin
     *
     * @return string
     */
    protected function isAdmin()
    {
        return $_SESSION['user']['id_category'] == UserModel::ADMIN_USER_CATEGORY;
    }

    /**
     * Check user is enabled
     *
     * @param int $id_user
     * @return string
     */
    protected function isUserEnabled($id_user)
    {
        $userData = $this->user->getUser($id_user);

        return $userData['status']=='enabled' ? true : false;
    }

    /**
     * Convert image to base64
     *
     * @param string $url
     * @return string
     */
    protected function convertToBase64($url)
    {
        $path = getcwd() . '/' . $url;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
    }
}