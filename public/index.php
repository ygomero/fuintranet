<?php

// Include  class
include('../app/shared/common/defines.php');
include('../app/shared/core/Route.php');
include('../app/shared/core/View.php');

//domain.com/[module]/[view]/
//domain.com/[api]/[method]/
$module = "home";
// Parse current url
$parsed_url = parse_url($_SERVER['REQUEST_URI']); //Parse Uri

if (isset($parsed_url['path'])) {
    $path = $parsed_url['path'];
} else {
    $path = '/';
}

if ($path != "/") {
    $sections = explode("/", $path);
    if (isset($sections[1])) {
        $module = $sections[1];
    }
}

echo View::render("home");
/*
// Add base route (startpage)
Route::add('/', function () {
    echo View::render("home");
});

Route::add('/home', function () {
    echo View::render("home");
});

Route::add('/profile', function () {
    echo View::render("profile");
});

Route::pathNotFound(function () {
    echo View::render("404",true);
});

Route::run('/');*/
