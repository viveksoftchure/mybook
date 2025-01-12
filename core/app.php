<?php
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/
require __DIR__.'/../config/config.php';
require __DIR__.'/../config/routes.php';
require __DIR__.'/../core/function.php';
require __DIR__.'/../models/model.php';
require __DIR__.'/../includes/controller.php';

class App
{
    /**
     * Connection to database
     *
     * @var array
     */
    protected $db;

    /**
     * Config app data
     *
     * @var array
     */
    protected $config;

    /**
     * Routes app data
     *
     * @var array
     */
    protected $routes;

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
     * Login controller
     *
     * @var string
     */
    protected $loginController = 'login';

    /**
     * Default controller
     *
     * @var string
     */
    protected $defaultController = 'home';

    /**
     * Error controller
     *
     * @var string
     */
    protected $errorController = 'fault';

    /**
     * Create app object
     *
     * @param array $config
     * @param array $routes
     */
    public function __construct($config, $routes)
    {
        $this->config = $config;
        $this->routes = $routes;
    }

    /**
     * Init app
     *
     * @var void
     */
    public function init()
    {
        $this->setConnection();
        $this->route();
    }

    /**
     * Perform app routing
     *
     * @return void
     */
    protected function route()
    {
        $t1 = sanitize(ifset($_GET,'t1'));
        $t2 = sanitize(ifset($_GET,'t2'));
        
        if (empty($_SESSION['connection'])) {
            $this->t1 = $this->loginController;
            $this->t2 = (in_array($t2, $this->routes['login'])) ? $t2 : 'login';
        } else {
            $this->t1 = ($t1) ? $t1 : $this->defaultController;
            $this->t2 = ($t2) ? $t2 : 'default';
        }

        # Check route and user permissions
        if (!isset($this->routes[$this->t1])) {
            if ($this->t1 != $this->loginController) {
                $this->t1 = $this->errorController;
                $this->t2 = 'error';
            }
        }   

        # Include language file
        $path = __DIR__ . '/../includes/' . $this->t1 . '.php';

        if (file_exists($path)) {
            $className = ucfirst($this->t1);
            $actionName = $this->t2 . 'Action';

            # Include controller
            require_once $path;
            $controller = new $className($this->t1, $this->t2, $this->db, $this->config);
            if (method_exists($controller, $actionName) && in_array($this->t2, $this->routes[$this->t1])) {
                $controller->preDispatch();
                $controller->$actionName();
                $controller->postDispatch();

            } else {
                $controller->errorAction();
            }
        }
    }

    /**
     * Set connection to DB
     *
     * @return void
     */
    protected function setConnection()
    {
        $this->db = new mysqli(
            $this->config['host'],
            $this->config['login'],
            $this->config['password'],
            $this->config['database']
        );

        if (!$this->db) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            die();
        }

        $this->db->set_charset('utf8');
    }
}

return new App($config, $routes);