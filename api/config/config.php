<?php
    class Config{
        //get file url
        public static function getFileUrl($key){
            $data = file_get_contents(__DIR__.'/../config/config.json');
            $config = json_decode($data,true);
            if(isset($config['files'])){
                //get files settings
                $files = $config['files'];
                //file location
                if(isset($files[$key]))
                    return $files[$key];
                else
                    return '';
            }
        }
    }
?>