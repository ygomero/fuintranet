<?php
class App {

    private static $pathGuards = DIR_APP.DS."domain".DS."guards";
    private static $pathControllers = DIR_APP.DS."domain".DS."controllers";
    
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


}