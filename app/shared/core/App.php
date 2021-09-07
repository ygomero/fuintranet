<?php 
class App {

    private static $pathGuards = DIR_APP.DS."domain".DS."guards";
    private static $pathControllers = DIR_APP.DS."domain".DS."controllers";
    private $connections = [];
    private $routes = [];
    private $pathNotFound = null;
    private $methodNotAllowed = null;
    private $currentModule = null;
    private $querys = [];

    public function __construct()
    {
    //   require_once(self::$pathControllers.DS.'BaseController.php');
        require_once(self::$pathControllers.DS.'LayoutController.php');
        require_once(self::$pathControllers.DS.'ModulesController.php');
    }

    function addQuerys($querys){
        $this->$querys = $querys;
    }

    function load_guard($guard){
        if(file_exists(self::$pathGuards.DS.ucfirst($guard).'Guard.php')){
            require_once(self::$pathGuards.DS.ucfirst($guard).'Guard.php');
        }
    }

    function load_controller($controller){
        if(file_exists(self::$pathControllers.DS.ucfirst($controller).'Controller.php')){
            require_once(self::$pathControllers.DS.ucfirst($controller).'Controller.php');
        }
    }

    function addConnection($name,$conn){
        $this->connections[$name] = $conn;
    }
    
    function getConnection($name){
        if(isset($this->connections[$name])){
            return $this->connections[$name];
        }
        else{
            return false;
        }
    }
    
    function addRoutes($routes){
        $this->routes = $routes;
        foreach($this->routes as $route){

            if(isset($route["controller"])){
                $this->load_controller($route["controller"]);
            }

            if(isset($route["guards"])){
                foreach($route["guards"] as $guard){
                    $this->load_guard($guard);
                }
            }
        }
    }

    public function pathNotFound($function)
    {
        $this->pathNotFound = $function;
    }

    public function methodNotAllowed($function)
    {
        $this->methodNotAllowed = $function;
    }

    function process($basepath = '/'){

        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']); //Parse Uri

        if (isset($parsed_url['path'])) {
            $path = $parsed_url['path'];
            $parts = explode("/",$path);
            if($parts[1] != ""){
                $module = $parts[1];
            }
        } else {
            $path = '/';
        }
        
        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;
        $route_match_found = false;

        //verificar ruta si existe y metodo
        foreach ($this->routes as $route) {

            // Check path match	
            if($path == $route['path']){
                $path_match_found = true;
                // Check method match
                if (strtolower($method) == strtolower($route['method'])) {

                    if ($basepath != '' && $basepath != '/') {
                        array_shift($matches); // Remove basepath
                    }

                    $route_match_found = true;
                    $this->currentModule = $route;
                    break;

                }
            }
        }

        // No matching route was found
        if (!$route_match_found) {

            // But a matching path exists
            if ($path_match_found) {
                header("HTTP/1.0 405 Method Not Allowed");
                if ($this->methodNotAllowed) {
                    call_user_func_array($this->methodNotAllowed, array($path, $method));
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                if ($this->pathNotFound) {
                    call_user_func_array($this->pathNotFound, array($path));
                }
            }
        }

        //modulo api es para peticiones ajax
        if($module === "api"){
            // $base = new App\Controllers\BaseController($this);
            $class = 'App\Controllers\\'.ucfirst($this->currentModule["controller"]).'Controller';
            $object = new $class($this);
            $response = $object->main();
            echo $response;exit;
        }
        else{
            if(isset($this->currentModule["guards"])){
                foreach($this->currentModule["guards"] as $guard){
                    $class = 'App\Guards\\'.ucfirst($guard).'Guard';
                    $class::main();
                }
            }

            //si $fullpage es false entonces la pagina se cargará dentro del layout, si es true se cargará sola
            $fullpage = false;
            if(isset($this->currentModule["fullpage"]) && $this->currentModule["fullpage"] === true){
                $fullpage = true;
            }
            View::render($this->currentModule["controller"],$fullpage);
        }   
    }
}
