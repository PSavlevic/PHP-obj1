<?php

/**
 * Saves array to file
 * @param type array. $array - array we want to convert
 * @param type file. $file_name - file into where we want to save
 * @return boolean
 */
function array_to_file($array, $file_name) {
    $array_encode_to_jason_string = json_encode($array);
    $success = file_put_contents($file_name, $array_encode_to_jason_string); //$success atiduoda irasyta baitu skaiciu arba false
    if ($success !== FALSE) {
        return true;
    } else {
        return false;
    }
}

/**
 * Decoding array from file
 * @param type file. $file_name - file we get data from
 * @return array|boolean
 */
function file_to_array($file_name) {
    if (file_exists($file_name)) {
        $encoded_string = file_get_contents($file_name);
        if ($encoded_string !== false) {
            return json_decode($encoded_string, true);
        } else {
            return false;
        }
    }
}
 ?>