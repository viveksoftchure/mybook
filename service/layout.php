<?php
/**
 * Service layout
 */
class Layout
{
    /**
     * Config app data
     *
     * @var array
     */
    protected $config;

    /**
     * Layout constructor
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Build path to template
     *
     * @param $template
     * @return string
     */
    public function getTemplatePath($template)
    {
        return __DIR__ . '/../templates/' . $template;
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
        $conf = $this->config;
        $t1 = ifset($data, 't1');
        $t2 = ifset($data, 't2');

        if ($fetch) {
            ob_start();
        }
        if ($include) {
            require $this->getTemplatePath('header.php');
        }
        require $this->getTemplatePath($template . '.php');
        if ($include) {
            require $this->getTemplatePath('footer.php');
        }
        if ($fetch) {
            return ob_get_clean();
        }
    }

    /**
     * Fetch view - include header, main template and footer
     *
     * @param string $template
     * @param $data
     * @param bool $include whether include header and footer or just show a template
     * @return false|string
     */
    public function fetchView($template, $data = [], $include = false)
    {
        return $this->renderView($template, $data, $include, true);
    }
}