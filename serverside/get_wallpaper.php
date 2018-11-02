<?php


$year = date('Y');
$month_word = strtolower(date('F'));
// Monatszahl muss -1 sein. z.B August = 07
$month = get_month_number();

$url = "https://www.smashingmagazine.com/" . $year . "/" . $month . "/desktop-wallpaper-calendars-" . $month_word . "-" . $year . "/";
$regex = "/http.*?.(png|jpg)/";

$resolutions = [
    '1920x1440',
    '2560x1440'
];

$data = file_get_contents($url);

preg_match_all($regex, $data, $matches, PREG_OFFSET_CAPTURE);
$urls = $matches[0];

$i = 0;
$j = 0;
$k = 0;

empty_folder($resolutions);
foreach ($resolutions as $resolution) {
    foreach ($urls as $url) {
        if (preg_match('/' . $resolution . '/', $url[0])) {
           // echo $url[0] . ' -- ';
            if (preg_match('/\/nocal\//', $url[0])) {
              //  echo $url[0] . ' || ';
                create_folder_if_not_exists('/srv/proengine/proengine-backend/public/prodoppler/wallpaper/', $resolution . '/nocalendar');
                download_remote_file($url[0], '/srv/proengine/proengine-backend/public/prodoppler/wallpaper/' . $resolution . '/nocalendar/' . $j . '.png');
                $j++;
            }
            else if(preg_match('/\/cal\//', $url[0])) {
               // echo $url[0] . ' ** ';
            //    echo $i . 'calendar '. $resolution . ' >< ' ;
                create_folder_if_not_exists('/srv/proengine/proengine-backend/public/prodoppler/wallpaper/', $resolution . '/calendar');
                download_remote_file($url[0], '/srv/proengine/proengine-backend/public/prodoppler/wallpaper/' . $resolution . '/calendar/' . $k . '.png');
                $k++;
            }
        }
    }
    $i = 0;

}

echo "success\n";
/**
 * @param $file_url
 * @param $save_to
 */
function download_remote_file($file_url, $save_to) {
    $content = file_get_contents($file_url);
    file_put_contents($save_to, $content);
}

/**
 * @param $resolutions
 */
function empty_folder($resolutions) {
    $calendars = [
        'calendar',
        'nocalendar'
    ];
    foreach ($resolutions as $resolution) {
        foreach ($calendars as $calendar) {
            $dir = '/srv/proengine/proengine-backend/public/prodoppler/wallpaper/' . $resolution . '/' . $calendar . '/';
            foreach (glob($dir . '*.*') as $v) {
                unlink($v);
            }
        }
    }
}

function create_folder_if_not_exists($path, $folder) {
    if (!file_exists($path . $folder)) {
        mkdir($path . $folder, 0777, true);
    }
}

function get_month_number() {
    $month_number = date('m') - 1;
    if ($month_number < 10) {
        $month_number = '0' . $month_number;
    }
    return $month_number;
}
