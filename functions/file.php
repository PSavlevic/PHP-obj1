<?php

/**
 * Saves array to JSON file
 * @param array $safe_input
 * @param string $filename
 * @return boolean
 */
function array_to_file($filtered_input, $filename) {
    $string = json_encode($filtered_input);
    $file = file_put_contents($filename, $string);

    if ($file !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * Decodes JSON array file to string
 * @param string $filename
 * @return array|boolean
 */
function file_to_array($filename) {
    if (file_exists($filename)) {
        $encoded_string = file_get_contents($filename);

        if ($encoded_string !== false) {
            return json_decode($encoded_string, true);
        }    
    }
    
    return false;
}

