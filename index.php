<?php
//fitravimas
function get_form_input($form) {
    $filter_parameters = [];
    foreach ($form['fields'] as $field_id => $field) {
        $filter_parameters[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
    }

    return filter_input_array(INPUT_POST, $filter_parameters);
}

//validatinam, ar yra "fields' arrejuje "validators", jei yra, sukam foreach ir paleidziam jame esamas funkcijas
function validate_form($safe_input, &$form) {
    foreach ($form['fields'] as $field_id => &$field){
        if (isset($field['validators'])){
            foreach ($field['validators'] as $validator){
                $validator($safe_input[$field_id], $field);
            }
        }
    }
}

//validatinam, ar laukelis nera tuscias. Jei tuscias, meta error (i spana) - "laukas tuscias"
function validate_not_empty($field_input, &$field) {
    if (strlen($field_input) == 0) {
        $field['error'] = 'Laukas tuscias';
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
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'placeholder' => 'Onutė'
        ],
        'second_name' => [
            'label' => 'Pavardė',
            'type' => 'text',
            'validators' => [
                'validate_not_empty',
            ],
            'value' => '',
            'placeholder' => 'Kimarinskienė'
        ]
    ]
];

$safe_input = get_form_input($form);
validate_form($safe_input, $form);

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
    <?php foreach ($form['fields'] as $key => $input): ?>
        <?php if (isset($input['label'])): ?>
            <label for="<?php print $key; ?>"><?php print $input['label']; ?></label>
        <?php endif; ?>

        <input name="<?php print $key; ?>" type="<?php print $input['type']; ?>" placeholder="<?php print $input['placeholder']; ?>">
        <?php if (isset($input['error'])): ?>
            <span class="error"><?php print $input['error']; ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
    <!-- TO DO -->
    <button>Submit</button>
</form>
</body>
</html>