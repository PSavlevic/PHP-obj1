<?php
$form = [
    'action' => 'index.php',
    'method' => 'POST',
    'fields' => [
        'first_name' => [
            'label' => 'Vardas:',
            'type' => 'text',
            'value' => '',
            'placeholder' => '',
            'validator' => [
                'validate_not_empty'
            ]
        ],
        'last_name' => [
            'label' => 'Pavarde:',
            'type' => 'text',
            'value' => '',
            'placeholder' => '',
            'validator' => [
                'validate_not_empty'
            ]
        ],
        'age' => [
            'label' => 'amzius:',
            'type' => 'number',
            'value' => '',
            'placeholder' => '',
            'validator' => [
                'validate_not_empty',
                'validate_is_number',
                'validate_is_positive',
                'validate_max_100',
            ]
        ]
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];



//filtered
function get_form_input($form) {
    $parameters = [];
    foreach ($form['fields'] as $field_id => $field){
        $parameters[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
    }
    return filter_input_array(INPUT_POST, $parameters);
}

$safe_input = get_form_input($form);
validate_form($safe_input, $form);

function validate_form($safe_input, &$form){
    $success = true;
    foreach ($form['fields'] as $field_id => &$field){
        $field_value = $safe_input[$field_id];
        $field['value'] = $field_value;
        if(isset($field['validator'])){
            foreach ($field['validator'] as $validator){
                $is_valid = $validator($safe_input[$field_id], $field);
                if(!$is_valid){
                    $success = false;
                    break;
                }
            }
        }
    }
    if($success) {
        $form['callbacks']['success']($safe_input, $form);
    } else {
        $form['callbacks']['fail']($safe_input, $form);
    }
    return $success;
}

function form_success($safe_input, &$form) {
    $array = json_encode($safe_input) . "\r\n";
    file_put_contents('info.txt', $array, FILE_APPEND);
}

function form_fail($safe_input, &$form) {
    print 'blogai';
}

function validate_not_empty($safe_input, &$field) {
    if(strlen($safe_input) == 0){
        $field['error'] = 'laukelis tuscias';
    } else {
        return true;
    }
}

    function validate_is_number($safe_input, &$field){
    if(!is_numeric($safe_input)) {
        $field['error'] = 'iveskit skaiciu';
    } else {
        return true;
    }
    }

    function validate_is_positive($field_input, &$field){
        if ($field_input <= 0) {
            $field['error'] = 'Iveskite teigiama skaiciu';
        } else {
            return true;
        }
    }

function validate_max_100($field_input, &$field){
    if ($field_input > 100) {
        $field['error'] = 'Iveskite skaiciu iki 100';
    } else {
        return true;
    }
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>06.25</title>
    <link rel="stylesheet" type="text/css" href="include/normalise.css">
    <link rel="stylesheet" type="text/css" href="include/style.css">
</head>
<body>
<form action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <?php foreach ($form['fields'] as $field_id => $field): ?>
        <label><?php print $field['label']; ?></label>
        <input type="<?php print $field['type']; ?>"
               name="<?php print $field_id; ?>"
            <?php if (isset($field['value'])): ?>
                value="<?php print $field['value']; ?>"
            <?php endif; ?>
            <?php if (isset($field['placeholder'])): ?>
                value="<?php print $field['placeholder']; ?>"
            <?php endif; ?>
        >
        <?php if (isset($field['error'])): ?>
            <span class="error"><?php print $field['error']; ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
    <button name="mygtukas">Siusti</button>
</form>
</body>
</html>