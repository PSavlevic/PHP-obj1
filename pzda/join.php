<?php
// Uzkraunam visus reikalingus failus
require 'config.php';
require 'functions/file.php';
require 'functions/html/builder.php';
require 'functions/form/core.php';

$nav = [
    [
        'url' => '/pzda/index.php',
        'title' => 'Home'
    ],
    [
        'url' => '/pzda/create.php',
        'title' => 'Create'
    ],
    [
        'url' => '/pzda/join.php',
        'title' => 'Joint'
    ],
    [
        'url' => '/pzda/play.php',
        'title' => 'Play'
    ],
];

$komandu_array = get_team_names();

$form = [
    'attr' => [
        //'action' => '', Nebūtina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [
        'player_name' => [
            'label' => 'Enter Player name',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Enter Name'
                ],
                'validators' => [
                    'validate_not_empty',
                    'validate_player'
                ]
            ],
        ],
        'team_select' => [
            'type' => 'select',
            'label' => 'Pasirink komanda',
            'value' => 1, // Koreliuoja su options pasirinkimo indeksu
            'options' => $komandu_array,
            'extra' => [
                'attr' => [
                    'class' => 'team-select-field',
                ],
                'validators' => [
                    'validate_not_empty'
                ]
            ]
        ],
    ],
    'buttons' => [
        'join' => [
            'title' => 'Join team',
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

function validate_player($field_input, &$field) {
    $file_data = file_to_array(STORAGE_FILE);
    if ($file_data) {
        foreach ($file_data as $team_id => $team) {
            if (in_array($field_input, $team['players'])) {
                $field['error'] = 'Toks zaidejas jau yra!';
                return false;
            }
        }
    }
    return true;
}

function get_team_names() {
    $komandos = [];
    $file_data = file_to_array(STORAGE_FILE);
    if ($file_data) {
        foreach ($file_data as $team_id => $team) {
            $komandos[] = $team['team_name'];
        }
    }
    return $komandos;
}

function form_fail($filtered_input, &$form) {
//    var_dump('Form failed!');
}

function form_success($filtered_input, &$form) {
    $data = [];
    $file_data = file_to_array(STORAGE_FILE);
    if ($file_data) {
        $data = $file_data;
    }
    $team_index = $filtered_input['team_select'];
    $team = &$file_data[$team_index];
    $team["players"][] = $filtered_input['player_name'];
    array_to_file($file_data, STORAGE_FILE);
    $player_data = json_encode([
        'name' => count($team['players']) - 1,
        'team_index' => $team_index
    ]);
    setcookie(PLAYER_COOKIE, $player_data, time() + 3600, '/');
//    $_COOKIE[PLAYER_COOKIE] = $player_data;
//    header('Location: /play.php');
}

if (empty($_COOKIE[PLAYER_COOKIE])) {

// Get all data from $_POST
    $input = get_form_input($form);

// If any data was entered, validate the input
    if (!empty($input)) {
        $success = validate_form($input, $form);
        $message = $success ? 'Nauja komanda sukurta' : 'Klaida!';
    }
} else {
    $form = [];
    $message = 'Tu jau prisireginai!';
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
        <?php require 'templates/navigation.tpl.php'; ?>        

        <?php if (isset($message)): ?>
            <div class="message">
                <span class="text"><?php print $message; ?></span>
                <span class="close">X</span>
            </div>
        <?php endif; ?>

        <!-- $form HTML generator -->
        <?php require 'templates/form.tpl.php'; ?>
    </body>
</html>
