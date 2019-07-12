<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function validate_not_empty($field_input, &$field) {
    if (strlen($field_input) == 0) {
        $field['error'] = 'Laukas tuščias';
    } else {
        return true;
    }
}

function validate_is_number($field_input, &$field) {
    if (!is_numeric($field_input)) {
        $field['error'] = 'Įveskite skaičių!';
    } else {
        return true;
    }
}

function validate_is_positive($field_input, &$field) {
    if ($field_input < 0) {
        $field['error'] = 'Įveskite teigiamą skaičių.';
    } else {
        return true;
    }
}
