<?php
/**
 * Auth model
 */
class Auth extends Model
{
    /**
     * Cookie size
     *
     * @var int
     */
    protected $cookieSize = 100;

    /**
     * Cookie lifetime (in seconds)
     *
     * @var int
     */
    protected $cookieLifetime = 1296000;

    /**
     * User model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * Auth model constructor
     *
     * @param mysqli $db
     * @param array $config
     */
    public function __construct($db, $config)
    {
        $this->user = new UserModel($db, $config);
        parent::__construct($db, $config);
    }

    /**
     * Logout user and clean session
     *
     * @return void
     */
    public function logout()
    {
        $_SESSION['connection'] = 0;

        // Remove cookie if exists
        if (isset($_COOKIE['theCookie'])) {
            $this->user->saveCookie($_SESSION['user']['id_user'], '');
            unset($_COOKIE['theCookie']);
            setcookie('theCookie', '', -1);
        }

        session_destroy();
        header('Location: ' . $this->config['url']);
    }

    /**
     * Login user
     *
     * @param string $login
     * @param string $password
     * @param bool $remember
     * @return bool
     */
    public function authByForm($login, $password, $remember = false)
    {
        if ($user = $this->user->getUserByCreds($login, $password)) {
           $result = $this->authUser($user);
            if ($result && $remember) {
                $cookie = authentication_string($this->cookieSize);
                $this->user->saveCookie($_SESSION['user']['id_user'], $cookie);
                setcookie('theCookie', $cookie, time() + $this->cookieLifetime, '/');
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Connect user
     *
     * @param int $id_user
     * @return bool
     */
    public function connectUser($id_user)
    {
        if ($user = $this->user->getItem($id_user)) {
            return $this->authUser($user, true);
        } else {
            return false;
        }
    }

    /**
     * Auth user
     *
     * @param array $user
     * @param bool $admin_connect
     * @return bool
     */
    protected function authUser($user, $admin_connect = false)
    {
        $_SESSION['connection'] = 1;
        $_SESSION['user']['id_user'] = $user['id_user'];
        $_SESSION['user']['first_name'] = $user['first_name'];
        $_SESSION['user']['last_name'] = $user['last_name'];
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['user']['id_category'] = $user['id_category'];
        $_SESSION['user']['menu'] = isset($user['menu_small']) ? $user['menu_small'] : 0;
        $_SESSION['user']['mode'] = isset($user['darkmode']) ? $user['darkmode'] : 0;

        $this->db->query("
            insert into `md_user_connect` (`id_user`,`date_connect`)
            values (
                " . $this->_escape($user['id_user']) . ",
                NOW()
            )"
        );

        return true;
    }

    /**
     * Auth user by cookie
     *
     * @param string $cookie
     * @return bool
     */
    public function authByCookie($cookie)
    {
        if (strlen($cookie) == $this->cookieSize) {
            $user = $this->user->getUserByCookie($cookie);
            if ($user) {
                return $this->authUser($user);
            }
        }
        return false;
    }

    /**
     * Reset user
     *
     * @param string $email
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function reset($email)
    {
        if ($user = $this->user->getUserByEmail($email)) {
            $reset = authentication_string(50);
            $this->user->setResetString($user['id_user'], $reset);
            $this->user->sendResetEmail($user['id_user'], $reset);
            return true;
        }
        return false;
    }
}