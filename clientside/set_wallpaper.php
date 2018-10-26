<?php
$resolution = '1920x1440';
$calendar_mode = 'nocalendar';
$wallpaper_url = 'https://prodoppler.proapp.ch/wallpaper/rest/get_wallpaper_information.php?resolution=' . $resolution . '&calendar_mode='. $calendar_mode;
$dir = '/Users/danilosantagata/Pictures/Wallpaper/';

//Inhalt des Get-Requests als Array speichern
$response = json_decode(file_get_contents($wallpaper_url), true);
$url_array = $response[$resolution][$calendar_mode];

empty_wallpaper_folder();

$i=0;
foreach($url_array as $url){
	$content = file_get_contents($url);
	file_put_contents($dir. $i. 'png', $content);
	$i++;	
}


function empty_wallpaper_folder(){
	$dir = '/Users/danilosantagata/Pictures/Wallpaper/';
	$files = glob($dir . '*');
	foreach($files as $file){
		unlink($file);
	}
}
