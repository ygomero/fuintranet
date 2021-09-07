<?php

use App\Controllers\ModulesController;
use App\Controllers\HomeController;
class App {

    private static $pathGuards = DIR_APP.DS."domain".DS."guards";
    private static $pathControllers = DIR_APP.DS."domain".DS."controllers";
    private static $pathLayout = DIR_APP.DS."web".DS."shared";
    private static $guards = [
        "auth"
    ];

    private static $controllers = [
        "modules"
    ];

    private static $layout = [
        "navbar"
    ];

    private static $connections = [];

    function __construct() {
        $this->load_guards(self::$guards);
        $this->load_controllers(self::$controllers);
        $this->load_layout(self::$layout);
    }

    function addConnection($name,$conn){
        self::$connections[$name] = $conn;
    }

    function getConnection($name){
        if(isset(self::$connections[$name])){
            return self::$connections[$name];
        }
        else{
            return false;
        }
    }
    
    function load_guards($guards = [])
    {
        $i = new FileSystemIterator(self::$pathGuards, FileSystemIterator::SKIP_DOTS);
        foreach ($guards as $guard) {
            require_once(self::$pathGuards.DS.$guard.DS.ucfirst($guard).'Guard.php');
        }
    }

    function load_controllers($controllers = [])
    {
        $i = new FileSystemIterator(self::$pathControllers, FileSystemIterator::SKIP_DOTS);
        foreach ($controllers as $controller) {
            require_once(self::$pathControllers.DS.$controller.'.php');
        }
    }

    function load_layout($components = [])
    {
        $i = new FileSystemIterator(self::$pathGuards, FileSystemIterator::SKIP_DOTS);
        foreach ($components as $component) {
            require_once(self::$pathLayout.DS.$component.DS.'main.php');
        }
    }

    function process($module,$function = "main"){
        $class = 'App\Controllers\\'.ucfirst($module).'Controller';
        $object = new $class();
        $object::$function();
    }
}