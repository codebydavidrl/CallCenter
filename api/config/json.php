<?php
    class Json{
        // convert list to json Array
        public static function listToArray($list){
            $array = array();
            foreach ($list as $item ) {
                #add item to array
                array_push($array,json_decode($item->toJson()));
            }
            return $array; // return array
        }
    }
?>