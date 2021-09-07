<?php

namespace App\Controllers;

use App\Controllers\ModulesController;

class LayoutController
{
    public $app = null;
    function __construct($app)
    {
        $this->app = $app;
    }

    private static $html_first_level = '
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
        <i class="flaticon-381-networking"></i>
        <span class="nav-text"><first-level-title></first-level-title></span>
        </a>
        <second-level></second-level>
    </li>
    ';

    private static $html_second_level = '
    <li>
        <a href="<second-level-url></second-level-url>"><second-level-title></second-level-title></a>
    </li>
    ';

    function getSideBar()
    {
        $modulesCtrl = new ModulesController($this->app);
        $modules = $modulesCtrl->getModules();
        $content = '';
        $result = self::getHtml1($modules,$content);
        return $result;
    }

    static function getHtml1($modules, &$content)
    {
        $main = '
        <ul class="metismenu" id="menu">
            <modules></modules>
        </ul>
        ';
        foreach ($modules as $module) {
            $content .= self::$html_first_level;
            $content = str_replace("<first-level-title></first-level-title>", $module["name"], $content);
            if (isset($module["childs"])) {
                $content2 = self::getHtml2($module["childs"], $content);
                $content = str_replace("<second-level></second-level>", $content2, $content);
            }
        }

        $main = str_replace("<modules></modules>", $content, $main);
        return $main;
    }
    static function getHtml2($modules)
    {

        $main = '
        <ul aria-expanded="false">
            <modules2></modules2>
        </ul>
        ';
        $content2 = "";
        foreach ($modules as $module) {
            if (isset($module["name"])) {
                $content2 .= self::$html_second_level;
                $content2 = str_replace("<second-level-title></second-level-title>", $module["name"], $content2);
                $content2 = str_replace("<second-level-url></second-level-url>", $module["route"], $content2);
                if (isset($module["childs"])) {
                    self::getHtml3($module["childs"], $content2);
                }
            }
        }
        $main = str_replace("<modules2></modules2>", $content2, $main);
        return $main;
    }
    static function getHtml3($modules)
    {
        foreach ($modules as $module) {
        }
    }
}
