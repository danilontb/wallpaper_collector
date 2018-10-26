<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 24.10.18
 * Time: 21:34
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('wallpaper_info_handler.php');

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('Status: 200');

$data = $_GET;

$wih = new wallpaper_info_handler();
echo json_encode($wih->get_information($data), true);


