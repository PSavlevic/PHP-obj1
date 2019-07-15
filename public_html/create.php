<?php
// Uzkraunam visus reikalingus failus
require '../config.php';
require ROOT . '/functions/file.php';
require ROOT . '/functions/html/builder.php';
require ROOT . '/functions/form/core.php';

$form = [
    'attr' => [
        //'action' => '', Nebūtina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [
        'team_name' => [
            'label' => 'Choose Team name',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Type team name here'
                ],
                'validators' => [
                    'validate_not_empty',
                    'validate_team_name_exists'
                ]
            ],
        ],
    ],
    'buttons' => [
        'create' => [
            'title' => 'Create team',
            'extra' => [
                'attr' => [
                    'class' => 'red-btn'
                ]
            ]
        ],
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

function validate_team_name_exists($field_input, &$field) {
    $file_data = file_to_array(STORAGE_FILE);
    if ($file_data) {
        foreach ($file_data as $team_id => $team) {
            if ($field_input == $team['team_name']) {
                $field['error'] = 'Tokia komanda jau yra!';
                return false;
            }
        }
    }
    return true;
}

function form_fail($filtered_input, &$form) {
//    var_dump('Form failed!');
}

function form_success($filtered_input, &$form) {
    $team = [
        'team_name' => $filtered_input['team_name'],
        'players' => [
        ],
        'score' => 0
    ];

    $new_data = [];
    $old_data = file_to_array(STORAGE_FILE);
    if ($old_data) {
        $data = $old_data;
    }

    $data[] = $team;
    array_to_file($data, STORAGE_FILE);
}

// Get all data from $_POST
$input = get_form_input($form);

// If any data was entered, validate the input
if (!empty($input)) {
    $success = validate_form($input, $form);
    $message = $success ? 'Nauja komanda sukurta' : 'Klaida!';
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome To PHP FightClub!</title>
        <link rel="stylesheet" href="media/css/normalize.css">
        <link rel="stylesheet" href="media/css/style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    
        <script src="media/js/app.js"></script>    
    </head>
    <body>
        <!-- $nav Navigation generator -->
        <?php require '../templates/navigation.tpl.php'; ?>

        <?php if (isset($message)): ?>
            <div class="message">
                <span class="text"><?php print $message; ?></span>
                <span class="close">X</span>
            </div>
        <?php endif; ?>

        <!-- $form HTML generator -->
        <?php require '../templates/form.tpl.php'; ?>
    </body>
</html>
