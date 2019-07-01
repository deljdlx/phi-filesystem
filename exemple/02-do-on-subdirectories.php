<?php


require(__DIR__.'/_autoload.php');


$path = new \Phi\FileSystem\Path(__DIR__.'/../..');


echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
print_r('scanning '. $path->normalize());
echo '</pre>';


print_r($path->doOnSubdirectories(function($path) {
    echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
    echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
    print_r($path);
    echo '</pre>';

    return true;
}));


