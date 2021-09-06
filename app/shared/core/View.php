<?php

use App\Web\Shared\Navbar;
class View
{
    private static $path_views = DIR_APP.DS."web".DS."modules";
    private static $path_main = DIR_APP.DS."web".DS."shared".DS."layout".DS."index.html";

    static function render($view,$alone = false){
        if($alone){
            $content = file_get_contents(self::$path_views.DS.$view.DS.'main.html');
            return $content;
        }
        else{
            $main = file_get_contents(self::$path_main);
            $content = file_get_contents(self::$path_views.DS.$view.DS.'main.html');
            $main = str_replace("<content></content>",$content,$main);
            $main = str_replace("<module-name></module-name>",ucfirst($view),$main);
            $main = str_replace("<app-sidebar></app-sidebar>",Navbar::getContent(),$main);
            return $main;
        }
    }
}