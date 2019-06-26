<?php



$form = [
    'action' => 'index.php',
    'method' => 'POST',
    'fields' => [
        'first_name' => [
            'label' => 'Vardas:',
            'type' => 'text',
            'value' => '',
            'placeholder' => 'Onute'
        ],
        'last_name' => [
            'label' => 'Pavarde:',
            'type' => 'text',
            'value' => '',
            'placeholder' => 'Kimarinskiene'
        ],
    ]
];
//parasyti funkcija get_form_input kuri isfiltruotu visas $form fieldu vertes, atejusias i $_POST masyva
function get_form_input ($form) {
    $filter_parameters= [];
    foreach ($form['fields'] as $fields_id => $field) {
        $filter_parameters= FILTER_SANITIZE_SPECIAL_CHARS;
    }
    return filter_input_array(INPUT_POST, $filter_parameters);
}

var_dump(get_form_input ($form));


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<form action="/<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <label>
        <?php foreach ($form['fields'] as $field_id => $field): ?>
            <?php if (isset($field['label'])): ?>
                <?php print $field['label']; ?> <br>
            <?php endif; ?>

            <input type="<?php print $field['type']; ?>"
                <?php if (isset($field['value'])): ?>
                    value="<?php print $field['value']; ?>"
                <?php endif; ?>
                   placeholder="<?php print $field['placeholder']; ?>" name="<?php print $field_id; ?>"><br>

        <?php endforeach; ?>
    </label>
    <br> <br>
    <button name="action" value="save">Save</button>
</form>

</body>
</html>