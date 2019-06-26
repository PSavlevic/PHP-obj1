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
            <?php print $field['label']; ?> <br>
            <input type="<?php print $field['type']; ?>" value="<?php print $field['value']; ?>"
                   placeholder="<?php print $field['placeholder']; ?>" name="<?php print $field_id; ?>"><br>
        <?php endforeach; ?>
    </label>
    <br> <br>
    <button name="action" value="save">Save</button>
</form>

</body>
</html>