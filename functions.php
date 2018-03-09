<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/26/2017
 * Time: 12:29 PM
 */

function dd($arr){

    echo '<pre>';
    print_r($arr);
     echo '</pre>';
     die;
}

function debug($arr){

    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function createMyText($data){
//    $data = file_get_contents("php://input");
//    $data = json_decode($data);
    ob_start();
    print_r($data);
    $res = ob_get_clean();
    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/myText.txt","wb");
    fwrite($fp,$res);
    fclose($fp);
}