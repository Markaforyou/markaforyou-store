<?php

class ControllerCommonTb extends Controller
{
    protected $registry;

    public function __construct($registry)
    {
   		$this->registry = $registry;
   	}

    public function index($front_controller)
    {
        $theme_config = $this->getThemeConfig();
        if (false == $theme_config) {
            return;
        }

        $basename = $theme_config['basename'];

        $theme_settings = $this->config->get($basename);
        if (empty($theme_settings)) {
            return;
        }

        define('TB_THEME_ROOT', realpath(DIR_SYSTEM . '../') . '/tb_themes/' . $basename);
        define('TB_EXTENSIONS_ROOT', realpath(DIR_SYSTEM . '../') . '/tb_extensions');

        require_once TB_THEME_ROOT . '/library/Utils.php';
        require_once TB_THEME_ROOT . '/library/Context.php';
        require_once TB_THEME_ROOT . '/library/CatalogDispatcher.php';

        $context = new TB_Context($this->registry, $theme_config, $this->config->get('config_store_id'), 'catalog');
        $dispatcher = new TB_CatalogDispatcher($context, $this->registry, $front_controller);
        $dispatcher->dispatch();
    }

    protected function getThemeConfig()
    {
        $themedir = $this->registry->get('config')->get('config_template');

        $config_file = realpath(DIR_SYSTEM . '../') . '/tb_themes/' . $themedir . '/config.php';
        if (!file_exists($config_file)) {
            return false;
        }

        $theme_config = require $config_file;
        if (!is_array($theme_config)) {
            trigger_error('Theme config structure is not array.', E_USER_ERROR);
            exit();
        }

        $theme_config['basename'] = $themedir;

        return $theme_config;
    }
}