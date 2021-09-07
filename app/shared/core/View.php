<?php

use App\Controllers\LayoutController;

class View{

    public static $app;

    static function setApp($app){
        self::$app = $app;
    }

    function render($view,$alone = false){
        if($alone){
            $content = file_get_contents(DIR_VIEWS.DS.$view.DS.'main.html');
            echo $content;exit;
        }
        else{
            $main = file_get_contents(DIR_LAYOUT.DS."index.html");
            $content = file_get_contents(DIR_VIEWS.DS.$view.DS.'main.html');
            $main = str_replace("<module-name></module-name>",ucfirst($view),$main);
            $layoutCrtl = new LayoutController(self::$app);
            $main = str_replace("<app-sidebar></app-sidebar>",$layoutCrtl->getSideBar(),$main);
            echo $main;exit;
        }
    }
}
