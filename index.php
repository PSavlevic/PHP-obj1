<?php

//setcookie("COOKIE_NAME", "COOKIE_VALUE", time() + 500, "/");
//
//var_dump($_COOKIE);


require 'functions/file.php';

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

function form_success($filtered_input, &$form) {
    $new_data = [];
    $old_data = file_to_array('info.txt');
    if (!empty($old_data)) {
        $new_data = $old_data;
    }
    $new_data[] = $filtered_input;
    array_to_file($new_data, 'info.txt');
}

//Kitas budas:
//function form_success($filtered_input, &$form) {
//    $encoded_filtered_input = json_encode($filtered_input) . "\r\n";
//    file_put_contents('info.txt', $encoded_filtered_input, FILE_APPEND);
//}

function form_fail($filtered_input, &$form) {
    print 'Nepavyko įrašyti informacijos į failą.';
}

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

function validate_max_100($field_input, &$field) {
    if ($field_input > 100) {
        $field['error'] = 'Tiek žmonės negyvena.';
    } else {
        return true;
    }
}

$form = [
    'action' => 'index.php',
    'method' => 'POST',
    'fields' => [
        'first_name' => [
            'label' => 'Vardas',
            'type' => 'text',
            'value' => '',
            'validators' => [
                'validate_not_empty',
            ],
            'placeholder' => 'Įveskite vardą.'
        ],
        'second_name' => [
            'label' => 'Pavardė',
            'type' => 'text',
            'validators' => [
                'validate_not_empty',
            ],
            'value' => '',
            'placeholder' => 'Įveskite pavardę.'
        ],
        'third_name' => [
            'label' => 'Pravardė',
            'type' => 'text',
            'validators' => [
                'validate_not_empty',
            ],
            'value' => '',
            'placeholder' => 'Įveskite pravardę.'
        ],
        'age' => [
            'label' => 'Amžius',
            'type' => 'text',
            'validators' => [
                'validate_not_empty',
                'validate_is_number',
                'validate_is_positive',
                'validate_max_100'
            ],
            'value' => '',
            'placeholder' => 'Įveskite amžių.'
        ],
        'digit' => [
            'label' => 'Telefono numeris',
            'type' => 'text',
            'placeholder' => 'only digits...',
            'value' => '',
            'filter' => FILTER_SANITIZE_NUMBER_INT,
        ],
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

$filtered_input = get_form_input($form);
$th_foreach = [];

if ($filtered_input) {
    validate_form($filtered_input, $form);
    $th_foreach = show_th($form);
}

$data = file_to_array('info.txt');
if (!$data) {
    $data = [];
};

function show_th($formos_array) {
    $th_array = [];
    foreach($formos_array['fields'] as $field_id => $field){
        $th_array[] = $field['label'];
    }
    return $th_array;
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP form</title>
    <link rel="stylesheet" type="text/css" href="css/normalise.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<form action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <?php foreach ($form['fields'] as $key => $input): ?>
        <?php if (isset($input['label'])): ?>
            <label for="<?php print $key; ?>"><?php print $input['label']; ?></label>
        <?php endif; ?>

        <input name="<?php print $key; ?>"
               type="<?php print $input['type']; ?>"
               placeholder="<?php print $input['placeholder']; ?>"
               value="<?php print $input['value']; ?>">
        <?php if (isset($input['error'])): ?>
            <span class="error"><?php print $input['error']; ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
    <button>Siusti</button>
</form>
<table>
    <tr>
        <?php foreach ($th_foreach as $th): ?>
            <th><?php print $th; ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($data as $row): ?>
        <tr>
            <?php foreach ($row as $column): ?>
                <td>
                    <?php print $column; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>