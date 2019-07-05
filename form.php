<?php

function validate_not_empty($field_input, &$field) {
    if (strlen($field_input) == 0) {
        $field['error'] = 'Enter team name!';
    } else {
        return true;
    }
}

function get_form_action() {
    return filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
}

function get_form_inputs($form) {
    $filter_parameters = [];
    foreach ($form['fields'] as $field_id => $field) {
        if (isset($field['filter'])) {
            $filter_parameters[$field_id] = $field['filter'];
        } else {
            $filter_parameters[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
        }
    }
    return filter_input_array(INPUT_POST, $filter_parameters);
}

function validate_form($filtered_input, &$form) {
    $success = true;
    foreach ($form['fields'] as $field_id => &$field) {
        $field['value'] = $filtered_input[$field_id];
        if (isset($field['validators'])) {
            foreach ($field['validators'] as $validator) {
                $is_valid = $validator($filtered_input[$field_id], $field);
                if (!$is_valid) {
                    $success = false;
                    break;
                }
            }
        }
    }
    if ($success) {
        if (isset($form['callbacks']['success'])) {
            $form['callbacks']['success']($filtered_input, $form);
        }
    } else {
        if (isset($form['callbacks']['fail'])) {
            $form['callbacks']['fail']($filtered_input, $form);
        }
    }
    return $success;
}

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