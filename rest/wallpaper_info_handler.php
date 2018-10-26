<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 24.10.18
 * Time: 22:06
 */

class wallpaper_info_handler
{
    const WALLPAPER_FOLDER_PATH = '/srv/proengine/proengine-backend/public/prodoppler/wallpaper/';
    const WALLPAPER_FOLDER_URL = 'https://prodoppler.proapp.ch/wallpaper/';

    /**
     * @param $data
     *
     * @return array
     */
    public function get_information($data) {
        $resolution = isset($data['resolution']) && sizeof($data['resolution']) > 0 ? $data['resolution'] : '';
        $calendar = isset($data['calendar_mode']) && sizeof($data['calendar_mode']) > 0 ? $data['calendar_mode'] : '';


        $all_folder = scandir(self::WALLPAPER_FOLDER_PATH);
        $wallpaper_folder = $this->filter_wallpaper_folder($all_folder);

        $paths = $this->get_all_paths($wallpaper_folder);
        $paths = $this->filter_resoultion($paths, $resolution);
        $paths = $this->filter_calendar($paths, $calendar);
        return $paths;
    }

    /**
     * @param $all_folder array
     *
     * @return array
     */
    private function filter_wallpaper_folder($all_folder) {
        $names = [];
        foreach ($all_folder as $folder_id => $folder_name) {
            if (preg_match('/\d{3}x\d{3}/', $folder_name)) {
                $names[] = $folder_name;
            }
        }

        return $names;
    }

    /**
     * @param array $wallpaper_folder
     *
     * @return array
     */
    private function get_all_paths(array $wallpaper_folder) {
        foreach ($wallpaper_folder as $folder) {
            $wallpaper_sub_folders = array_diff(scandir(self::WALLPAPER_FOLDER_PATH . $folder), array('.', '..'));
            foreach ($wallpaper_sub_folders as $wallpaper_sub_folder_id => $wallpaper_sub_folder_name) {
                $fileList = glob(self::WALLPAPER_FOLDER_PATH . $folder . '/' . $wallpaper_sub_folder_name . '/*');

                $url_filelist = [];
                foreach ($fileList as $file) {
                    $url_filelist[] = str_replace(self::WALLPAPER_FOLDER_PATH, self::WALLPAPER_FOLDER_URL, $file);
                }
                $folder_subfolder[$folder][$wallpaper_sub_folder_name] = $url_filelist;

            }
        }
        //   return [];
        return $folder_subfolder;
    }

    protected function filter_resoultion($path, $resolution = '') {
        if (!empty($resolution)) {
            foreach ($path as $resokey => $calendararray) {
                if ($resokey !== $resolution) {
                    unset($path[$resokey]);
                }
            }
        }
        return $path;
    }

    protected function filter_calendar($path, $calendar = '') {
        if (!empty($calendar)) {
            foreach ($path as $resolution_key => $resolution_value) {
                foreach ($resolution_value as $calendar_key => $calendar_value) {
                    if ($calendar_key !== $calendar) {
                        unset($path[$resolution_key][$calendar_key]);
                    }
                }
            }
        }

        return $path;
    }
}