<?php

require 'validators.php';

/**
 * Sanitizes submitted form fields
 * @param array $form
 * @return array sanitized (filtered) data
 */
function get_form_input($form) {
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

/**
 * Sanitizes submitted form action (button) value
 * @return string
 */
function get_form_action() {
    return filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Validates entered data against 
 * field validators defined in $form
 * 
 * @param array $filtered_input filtered POST data
 * @param array $form
 * @return boolean
 */
function validate_form($filtered_input, &$form) {
    $success = true;

    foreach ($form['fields'] as $field_id => &$field) {
        $field_value = $filtered_input[$field_id];
        $field['value'] = $field_value;
        
        foreach ($field['extra']['validators'] ?? [] as $validator) {
            $is_valid = $validator($field_value, $field);
            if (!$is_valid) {
                $success = false;
                break;
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
