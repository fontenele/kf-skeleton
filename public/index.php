<?php

namespace Kf;

class Kernel {

    /**
     * @var array
     */
    public static $config = [];

    /**
     * @var View\Layout
     */
    public static $layout;

    /**
     * @var Http\Request
     */
    public static $request;

    /**
     * @var System\Router
     */
    public static $router;

    /**
     * @var Database\Pdo
     */
    public static $db;

    /**
     * @var boolean
     */
    public static $logged = false;

    public static function app() {
        try {
            self::bootLoader();
            self::setConstants();
            self::loadLibs();
            self::loadSystemConfigs();
            self::loadLogger();
            self::loadRequest();
            self::loadRouter();
            self::loadDatabase();
            $valid = self::acl();
            if ($valid) {
                self::run();
            }
        } catch (System\Exception\ACLException $ex) {
            
        } catch (System\Exception\DatabaseException $ex) {
            
        } catch (System\Exception\RouterException $ex) {
            
        } catch (\Exception $ex) {
            if (isset(self::$config['system']['router']['error'][$ex->getCode()])) {
                $session = new System\Session('errorInfo');
                $session->info = [
                    'message' => $ex->getMessage(),
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => $ex->getTrace(),
                    'previous' => $ex->getPrevious()
                ];
                return self::callAction(self::$config['system']['router']['error'][$ex->getCode()]['controller'], self::$config['system']['router']['error'][$ex->getCode()]['action'], self::$request);
            }
            xd($ex);
        }
    }

    public static function bootLoader() {
        try {
            date_default_timezone_set('America/Sao_Paulo');
            session_start();
            chdir(dirname(__DIR__));
            set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__));
            spl_autoload_register(function($className) {
                $router = Kernel::$router;
                if ($router) {
                    $realPath = $router::getRealPath($className);
                    if ($realPath) {
                        require_once($realPath);
                    }
                }
            });
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function setConstants() {
        define('APP_PATH', dirname(__DIR__) . '/');
        define('VENDOR_PATH', dirname(__DIR__) . '/vendor/');
        define('PUBLIC_PATH', dirname(__DIR__) . '/public/');
        define('TMP_PATH', dirname(__DIR__) . '/public/tmp/');
        define('LOG_PATH', dirname(__DIR__) . '/logs/');
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'prod'));
    }

    public static function loadLibs() {
        try {
            require_once('vendor/autoload.php');
            require_once(VENDOR_PATH . 'kf/kf/src/Kf/fdebug.php');
            require_once(VENDOR_PATH . 'kf/kf/src/Kf/System/Exceptions.php');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadSystemConfigs() {
        try {
            $envs = ['dev', 'hom', 'prod'];
            $filesIgnore = [];

            $dir = new \Kf\System\Dir(APP_PATH . 'config');
            $files = $dir->getFiles()->getArrayCopy();
            sort($files);
            foreach ($files as $file) {
                $filename = "{$dir->dirName}/{$file}";
                if (in_array($filename, $filesIgnore)) {
                    continue;
                }

                if (System\File::fileExists($filename, APPLICATION_ENV)) {
                    $filesIgnore[] = $filename;
                    $filename = System\File::getFileName($filename, APPLICATION_ENV);
                    $filesIgnore[] = $filename;
                } else {
                    foreach ($envs as $env) {
                        if ($env == APPLICATION_ENV) {
                            continue;
                        }
                        if (System\File::fileExists($filename, $env)) {
                            $filesIgnore[] = $filename;
                            $filesIgnore[] = System\File::getFileName($filename, $env);
                        }
                    }
                }

                $config = System\File::loadFile($filename);
                self::$config = array_merge_recursive(self::$config, $config);
            }

            self::loadModulesConfigs();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadModulesConfigs() {
        try {
            $envs = ['dev', 'hom', 'prod'];
            $modules = [];

            foreach (self::$config['system']['modules'] as $module) {
                switch (true) {
                    case is_dir(APP_PATH . "modules/{$module}"):
                        $modules[] = APP_PATH . "modules/{$module}/";
                        break;
                    case is_dir(APP_PATH . "vendor/{$module}/src/{$module}"):
                        $modules[] = APP_PATH . "vendor/{$module}src/{$module}/";
                        break;
                    case is_dir(APP_PATH . "vendor/{$module}/{$module}/src/{$module}/"):
                        $modules[] = APP_PATH . "vendor/{$module}/{$module}/src/{$module}/";
                        break;
                }
            }

            foreach ($modules as $module) {
                if (is_dir("{$module}config")) {
                    $filesIgnore = [];
                    $dir = new \Kf\System\Dir("{$module}config");
                    $files = $dir->getFiles()->getArrayCopy();
                    sort($files);
                    foreach ($files as $file) {
                        $filename = "{$dir->dirName}/{$file}";
                        if (in_array($filename, $filesIgnore)) {
                            continue;
                        }

                        if (System\File::fileExists($filename, APPLICATION_ENV)) {
                            $filesIgnore[] = $filename;
                            $filename = System\File::getFileName($filename, APPLICATION_ENV);
                            $filesIgnore[] = $filename;
                        } else {
                            foreach ($envs as $env) {
                                if ($env == APPLICATION_ENV) {
                                    continue;
                                }
                                if (System\File::fileExists($filename, $env)) {
                                    $filesIgnore[] = $filename;
                                    $filesIgnore[] = System\File::getFileName($filename, $env);
                                }
                            }
                        }

                        $config = System\File::loadFile($filename);
                        self::$config = array_merge_recursive(self::$config, $config);
                    }
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadLogger() {
        try {
            System\Logger::setDefaults();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadRequest() {
        try {
            self::$request = new Http\Request();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadRouter() {
        try {
            self::$router = new System\Router(self::$config['system']['router']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadDatabase() {
        try {
            if (isset(self::$config['db']['type'])) {
                switch (self::$config['db']['type']) {
                    case 'pgsql':
                        self::$db = new Database\Postgres(self::$config['db']);
                        break;
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function acl() {
        try {
            if (self::$config['system']['acl']['enabled']) {
                $session = new System\Session('system');
                if (!$session->offsetExists('identity')) {
                    self::$logged = false;
                    self::run(self::$router->defaultAuth);
                    return false;
                }
            }

            self::$logged = true;
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function run($route = null) {
        try {
            $controller = null;
            $action = null;

            if ($route) {
                $route = self::$router->parseRoute($route);
                $controller = $route['controller'];
                $action = $route['action'];
            } else {
                $controller = self::$router->getController();
                $action = self::$router->getAction();
                if (!$controller || !$action) {
                    $route = self::$router->parseRoute(self::$router->default);
                    $controller = $route['controller'];
                    $action = $route['action'];
                }
            }

            self::callAction($controller, $action, self::$request);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function createLayout() {
        try {
            self::$layout = new View\Layout('public/themes/' . self::$config['system']['view']['theme'] . '/view/' . self::$config['system']['view']['layout']);
            self::$layout->success = System\Messenger::getSuccess();
            self::$layout->error = System\Messenger::getError();
            self::$layout->userLogged = self::$logged;
            self::$layout->config = self::$config;
            self::$layout->theme = self::$config['system']['view']['theme'];
            self::$layout->themePath = self::$router->basePath . 'themes/' . self::$config['system']['view']['theme'] . '/';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadJsAndCssFiles($controller, $action) {
        $jsAndCss = ['css' => [], 'js' => []];
        $arrController = explode('\\', $controller);
        $_module = System\String::camelToDash($arrController[0]);
        $_controller = System\String::camelToDash($arrController[2]);
        $_action = System\String::camelToDash($action);

        if (file_exists(sprintf(APP_PATH . "public/%s/modules/{$_module}/{$_controller}/{$_action}.%s", 'css', 'css'))) {
            $jsAndCss['css'][] = sprintf(self::$router->basePath . "%s/modules/{$_module}/{$_controller}/{$_action}.%s", 'css', 'css');
        } else {
            if (file_exists(sprintf(APP_PATH . "vendor/{$arrController[0]}/{$arrController[0]}/src/{$arrController[0]}/public/%s/{$_controller}/{$_action}.%s", 'css', 'css'))) {
                $file = base64_encode(System\Crypt::encode(sprintf(APP_PATH . "vendor/{$arrController[0]}/{$arrController[0]}/src/{$arrController[0]}/public/%s/{$_controller}/{$_action}.%s", 'css', 'css')));
                $jsAndCss['css'][] = self::$router->basePath . "admin/file/css/file/{$file}";
            }
        }

        if (file_exists(sprintf(APP_PATH . "public/%s/modules/{$_module}/{$_controller}/{$_action}.%s", 'js', 'js'))) {
            $jsAndCss['js'][] = sprintf(self::$router->basePath . "%s/modules/{$_module}/{$_controller}/{$_action}.%s", 'js', 'js');
        } else {
            if (file_exists(sprintf(APP_PATH . "vendor/{$arrController[0]}/{$arrController[0]}/src/{$arrController[0]}/public/%s/{$_controller}/{$_action}.%s", 'js', 'js'))) {
                $file = base64_encode(System\Crypt::encode(sprintf(APP_PATH . "vendor/{$arrController[0]}/{$arrController[0]}/src/{$arrController[0]}/public/%s/{$_controller}/{$_action}.%s", 'js', 'js')));
                $jsAndCss['js'][] = self::$router->basePath . "admin/file/js/file/{$file}";
            }
        }
        return $jsAndCss;
    }

    public static function callAction($controller, $action, $request) {
        try {
            self::createLayout();
            if (isset(self::$config['system']['view']['afterRenderLayout'])) {
                foreach (self::$config['system']['view']['afterRenderLayout'] as $method) {
                    $method->render(self::$layout);
                }
            }

            $cssAndJs = self::loadJsAndCssFiles($controller, $action);
            $controller = '\\' . $controller;

            if (!class_exists($controller)) {
                throw new \Exception("Controller {$controller} not found.", 404);
            }

            // Instance controller
            $obj = new $controller($action, $request);

            if (!method_exists($obj, $action)) {
                throw new \Exception("Action {$controller}::{$action} not found.", 404);
            }

            $cssAndJs['css'] = array_merge(self::$layout->css, $cssAndJs['css']);
            $cssAndJs['js'] = array_merge(self::$layout->js, $cssAndJs['js']);
            self::$layout->css = $cssAndJs['css'];
            self::$layout->js = $cssAndJs['js'];
            
            // Call action
            $view = $obj->$action($request);

            if ($view instanceof View\Json) {
                // Render Json output
                echo $view->render();
            } else {
                // Set content var
                self::$layout->content = $view ? $view->render() : $obj->view->render();

                // Render layout with html headers
                echo self::$layout->renderWithHeader();
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

\Kf\Kernel::app();
