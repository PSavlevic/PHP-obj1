<?php
/**
 * Issaugo arreju i faila
 * @param array $array
 * @param string $file
 * @return boolean
 */
function array_to_file($array, $file) {
    $encoded_array = json_encode($array);
    $success = file_put_contents($file, $encoded_array);
    if($success !== false) {
        return true;
    } else {
        return false;
    }
}


/**
 * Decoding array from file
 * @param string $filename
 * @return bool|mixed
 */
//function file_to_array ($filename) {
//    if(file_exists($filename)) {
//        $encoded_string = file_get_contents($filename);
//        if ($encoded_string !== false) {
//            return json_decode($encoded_string, true);
//        }
//    }
//        return false;
//}


function file_to_array($file) {
    if (file_exists($file)) {
        $encoded_string = file_get_contents($file);
        if ($encoded_string !== false) {
            return json_decode($encoded_string, true);
        }
    }
    return false;
}

?>