<?php

class View
{
    private static $path_views = DIR_APP.DS."web".DS."modules";
    private static $path_main = DIR_APP.DS."web".DS."shared".DS."layout".DS."index.html";

    static function render($view){
        $main = file_get_contents(self::$path_main);
        $content = file_get_contents(self::$path_views.DS.$view.DS.'main.html');
        $main = str_replace("<content></content>",$content,$main);
        return $main;
    }
}