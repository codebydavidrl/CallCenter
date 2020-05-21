<?php

    class Config{
        public static function getFileUrl($key){
            $data=file_get_contents(__DIR__.'/../config/config.json');
            $config=json_decode($data,true);
            if (isset($config['files'])) {
                # code...
                $files=$config['files'];
                if (isset($files[$key])) {
                    # code...
                    return $files[$key];
                }else
                return '';

            }
        }

    }

        
    
    

?>