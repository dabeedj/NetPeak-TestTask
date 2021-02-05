<?php

/**
 * Router Class
 * Routes visitor's requests 
 */
class Router
{

    /**
     * routes array
     * @var array 
     */
    private $routes;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Routes file path
        $routesPath = ROOT . '/config/routes.php';

        // Gettinf routes
        $this->routes = include($routesPath);
    }

    /**
     * Returns query string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * Main class method
     */
    public function run()
    {
        // Getting query string
        $uri = $this->getURI();
        // echo '<pre>';
        // Checking query with existing routes (routes.php)
        foreach ($this->routes as $uriPattern => $path) {

            // Matching $uriPattern and $uri
            // echo $uri . ' - ';
            // echo $uriPattern . ' - ';
            // echo $path . PHP_EOL;
            if (preg_match("~$uriPattern~", $uri)) {

                // Getting internal route with pattern
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Defining controller, action and params

                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                // Including controller class
                $controllerFile = ROOT . '/controllers/' .
                        $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                // Creating class instance
                $controllerObject = new $controllerName;

                // Calling needed method in controller with parans 
                
                $result = null;

                if (method_exists($controllerObject, $actionName)
                   && is_callable([$controllerObject, $actionName]))
                {
                    $result = call_user_func_array([$controllerObject, $actionName], $parameters);
                }

                // Finish on successful call
                if ($result != null) return;
            }
        }
        // Else respond with 404
        http_response_code(404);
        die;
    }

}
