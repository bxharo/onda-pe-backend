<?php

class Bootstrap {
    protected static $THEME_APP_PATH;

    public static function config(): void {
        // __DIR__ es .../onda-pe-theme/app/config
    // Subimos un nivel para llegar a la carpeta 'app'
    self::$THEME_APP_PATH = dirname(__DIR__);

    // El vendor está en la raíz del tema (un nivel arriba de 'app')
    $theme_root = dirname(self::$THEME_APP_PATH);
    
    require_once($theme_root . '/vendor/autoload.php');

        self::setEnviromentVariables();
        self::loadCoreFiles();
        self::loadAdminPages();
        self::loadEnqueues();
        self::loadContentTypes();
        self::loadFilters();
    }

    private static function loadCoreFiles(): void {
        $theme_root = dirname(self::$THEME_APP_PATH);

        // Rutas basadas en tu estructura de carpetas real
        require_once($theme_root . '/api/main.php');
        require_once(self::$THEME_APP_PATH . '/helpers/AppHelper.php');
        require_once(self::$THEME_APP_PATH . '/pages/routes.php');
        require_once(self::$THEME_APP_PATH . '/config/setup.php');
        require_once(self::$THEME_APP_PATH . '/fields/config.php');
        require_once(self::$THEME_APP_PATH . '/plugins/main.php');
    }

    public static function setEnviromentVariables(): void {
        // Subimos un nivel desde THEME_APP_PATH (/app) para llegar a la raíz del tema
        $theme_root = dirname(self::$THEME_APP_PATH);
        
        // Si usas la versión más reciente de Dotenv:
        $dotenv = \Dotenv\Dotenv::createImmutable($theme_root);

        // Esto evita el Fatal Error si el archivo no existe, pero lo ideal es que lo encuentre
        try {
            $dotenv->load();
            // Crea la constante 'ENV' usando el valor de 'APP_ENV' del .env
            if (!defined('ENV')) {
            define('ENV', $_ENV['APP_ENV'] ?? 'development');
        }
        } catch (\Exception $e) {
            error_log("PANDA LOG: No se pudo cargar el archivo .env en " . $theme_root);

            if (!defined('ENV')) {
            define('ENV', 'production');
            }
        }
    }

    public static function loadAdminPages(): void {
        $adminPagesFolder = AppHelper::autoload_folders_by_folder('admin/pages');

        foreach($adminPagesFolder as $folder) {
            require_once get_theme_file_path("admin/pages/{$folder}/main.php");
        }
    }

    public static function loadContentTypes(): void {
        array_map(function ($file) {
            require_once get_theme_file_path("content_types/post_types/") . "{$file}";
        }, AppHelper::autoload_files_by_folder('/content_types/post_types'));

        array_map(function ($file) {
            require_once get_theme_file_path("content_types/taxonomies/") . "{$file}";
        }, AppHelper::autoload_files_by_folder('/content_types/taxonomies'));
    }

    public static function loadFilters(): void {
        array_map(function ($file) {
            require_once get_theme_file_path("filters/") . "{$file}";
        }, AppHelper::autoload_files_by_folder('/filters'));
    }

    public static function loadEnqueues(): void {
        require_once get_theme_file_path("functions/") . "enqueues.php";
    }
}
