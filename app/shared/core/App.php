<?php
class App {

    private static $pathGuards = DIR_APP.DS."domain".DS."guards";
    
    function load_guards($guards = [])
    {
        $i = new FileSystemIterator(self::$pathGuards, FileSystemIterator::SKIP_DOTS);
        foreach ($guards as $guard) {
            require_once(self::$pathGuards.DS.$guard.DS.ucfirst($guard).'Guard.php');
        }
    }
}