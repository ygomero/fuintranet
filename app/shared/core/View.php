<?php

use App\Controllers\LayoutController;

class View{

    public static $app;

    static function setApp($app){
        self::$app = $app;
    }

    static function render($view,$alone = false){
        if($alone){
            $content = file_get_contents(DIR_VIEWS.DS.$view.DS.'main.html');
            echo $content;exit;
        }
        else{
            $main = file_get_contents(DIR_LAYOUT.DS."index.html");
            $content = file_get_contents(DIR_VIEWS.DS.$view.DS.'main.html');
            $main = str_replace("<content></content>",$content,$main);
            $main = str_replace("<module-name></module-name>",ucfirst($view),$main);

            if(file_exists(DIR_VIEWS.DS.$view.DS.'scripts.html')){
                $js = file_get_contents(DIR_VIEWS.DS.$view.DS.'scripts.html');
                $main = str_replace("<extra-js></extra-js>",$js,$main);
            }

            $user = $_SESSION["user"]; 
            $html_session = "<span>{$user->name}</span>
            <small>{$user->namePerfil}</small>";
            $main = str_replace("<user-session></user-session>",$html_session,$main);
            $layoutCrtl = new LayoutController(self::$app);
            $main = str_replace("<app-sidebar></app-sidebar>",$layoutCrtl->getSideBar(),$main);
            echo $main;exit;
        }
    }
}
