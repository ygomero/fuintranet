<?php
class App {

    private static $pathGuards = DIR_APP.DS."domain".DS."guards";
    
    function load_guards($guards = [])
    {
        $i = new FileSystemIterator(self::$pathGuards, FileSystemIterator::SKIP_DOTS);
        foreach ($guards as $guard) {
            
            require_once($self::$path.$class_name . '.php');
            //only require the class once, so quit after to save effort (if you got more, then name them something else return
        }
    
        // if (file_exists($path_to_file)) {
        //     require $path_to_file;
        // }
    }
}