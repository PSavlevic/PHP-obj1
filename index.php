<?php
require 'functions/file.php';

function delete_all_cookies() {
    foreach ($_COOKIE as $cookie_key => $cookie_value) {
        setcookie($cookie_key, null, -1, "/");
    }
}
function get_form_action() {
    return filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
}

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
    setcookie('form', true, time() + 3600, '/');
    $_COOKIE['form'] = true;
}

//kai FAILAS, ivesti inputai po refreshinimo lieka field'u value is COOKIE(klientas gali testi pildyti forma o ne pildyti is naujo);
function form_fail($filtered_input, &$form) {
    //1.encodinam (paverciam array i string) inputus, nes i COOKIE negalime ideti arrejauso tik stringa
    $encoded = json_encode($filtered_input);
    //2.idedam json stringa i COOKIE and go to 156 line
    setcookie('cookieName', $encoded, time() +100, '/');
}

function validate_not_empty($field_input, &$field) {
    if (strlen($field_input) == 0) {
        $field['error'] = 'Laukas tuščias';
        setcookie('name', $field_input,time() +100, '/');
    } else {
        return true;
    }
}
function validate_is_number($field_input, &$field) {
    if (!is_numeric($field_input)) {
        $field['error'] = 'Įveskite skaičių!';
        setcookie('name', $field_input,time() +100, '/');
    } else {
        return true;
    }
}
function validate_is_positive($field_input, &$field) {
    if ($field_input < 0) {
        $field['error'] = 'Įveskite teigiamą skaičių.';
        setcookie('name', $field_input,time() +100, '/');
    } else {
        return true;
    }
}
function validate_max_100($field_input, &$field) {
    if ($field_input > 100) {
        $field['error'] = 'Tiek žmonės negyvena.';
        setcookie('name', $field_input,time() +100, '/');
    } else {
        return true;
    }
}
$form = [
    'action' => '',
    'method' => 'POST',
    'fields' => [
        'first_name' => [
            'label' => 'Vardas',
            'type' => 'text',
            'value' => '',
            'id' => 'vardenis',
            'validators' => [
                'validate_not_empty',
            ],
            'placeholder' => 'Įveskite vardą.'
        ],
        'second_name' => [
            'label' => 'Pavardė',
            'type' => 'text',
            'id' => 'pavardenis',
            'validators' => [
                'validate_not_empty',
            ],
            'value' => '',
            'placeholder' => 'Įveskite pavardę.'
        ],
        'age' => [
            'label' => 'age',
            'type' => 'text',
            'id' => 'age',
            'validators' => [
                'validate_not_empty',
                'validate_max_100',
                'validate_is_positive',
                'validate_is_number'
            ],
            'value' => '',
            'placeholder' => 'Age'
        ],
    ],
    'buttons' => [
        'submit' => [
            'type' => 'button',
            'title' => 'Submit',
        ],
        'reset' => [
            'type' => 'button',
            'title' => 'Istrinti Cookies'
        ]
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

// 3.po $form array dekodinam is cookie['name'] is stringo i array ir foreachinam forma ir idedam i $value duomenys
if(!empty($_COOKIE)) {
    $decoded = json_decode($_COOKIE['cookieName'], true);
    foreach ($form['fields'] as $field_id => &$field) {
        $field['value'] = $decoded[$field_id];
    }
}

$filtered_input = get_form_input($form);
$filtered_action = get_form_action();

switch (get_form_action()) {
    case 'submit':
        $success = validate_form($filtered_input, $form);
        if ($success) {
            $form['fields'] = [];
            unset($form['buttons']['submit']);
        }
        break;
    case 'reset':
        delete_all_cookies();
        break;
}
function get_table_headers($formos_array) {
    $th_array = [];
    foreach ($formos_array['fields'] as $field_id => $field) {
        $th_array[] = $field['label'];
    }
    return $th_array;
}
$table = [
    'headers' => get_table_headers($form),
    'rows' => file_to_array('info.txt')
];
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP form</title>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<form action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <?php foreach ($form['fields'] as $key => $input): ?>
        <?php if (isset($input['label'])): ?>
            <label for="<?php print $input['id']; ?>"><?php print $input['label']; ?></label>
        <?php endif; ?>

        <input name="<?php print $key; ?>"
               type="<?php print $input['type']; ?>"
               id="<?php print $input['id']; ?>"
               placeholder="<?php print $input['placeholder']; ?>"
               value="<?php print $input['value']; ?>">

        <?php if (isset($input['error'])): ?>
            <span class="error"><?php print $input['error']; ?></span>
        <?php endif; ?>

    <?php endforeach; ?>
    <?php foreach ($form['buttons'] as $button_key => $button): ?>
        <button name="action" value="<?php print $button_key; ?>">
            <?php print $button['title']; ?>
        </button>
    <?php endforeach; ?>
</form>
<?php if (!empty($_COOKIE)): ?>
    <table>
        <thead>
        <?php foreach ($table['headers'] as $header): ?>
            <th><?php print $header; ?></th>
        <?php endforeach; ?>
        </thead>
        <tbody>
        <?php if ($table['rows']): ?>
            <?php foreach ($table['rows'] as $row): ?>
                <tr>
                    <?php foreach ($row as $column): ?>
                        <td>
                            <?php print $column; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>

<!--Get_form_inputs   - filtruojam inputus (grazina $filtered_input array)-->
<!--Get_form_action – filtruojam buttonus (grazina submit arba reset)-->
<!---->
<!--Switch:-->
<!--Kai submit –-->
<!--Isima mygtuka is $form array ir paleidzia funkcija Validate_form --->
<!--Kai reset –-->
<!--Delete_all_cookies-->
<!---->
<!--Validate_form – sutikrina esamus validatorius laukams ir grazina $success = true arba false ir jei $success yra true, tai paleidzia form_success-->
<!---->
<!--Form_success – suraso ir senus ir naujus duomenis I info.txt ir iraso cookies-->
<!---->
<!--HTML paleidzia Get_Table_headers - KODEL nespausdina headeriu po pirmo paspaudimo( kai viskas tuscia).-->
<!---->
<!--HTML paleidzia file_to_array foreacha ir isspausdina duomenis.-->