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



$form = [
    'attr' => [
        //'action' => '', Nebūtina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [],
    'buttons' => [
        'kick' => [
            'title' => 'Kick the ball',
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
    $teams = file_to_array(STORAGE_FILE);
    if($teams){
        $decoded_player_array = json_decode($_COOKIE[PLAYER_COOKIE], true);
        $player_team_index = $decoded_player_array['team_index'];
//        $player_index = $decoded_player_array['player_index'];
        $team = &$teams[$player_team_index];
        $team['score'] += 1;
        array_to_file($teams, STORAGE_FILE);
        var_dump($team);
    }
}

if (!empty($_COOKIE[PLAYER_COOKIE])) {

// Get all data from $_POST
    $action = get_form_action($form);

// If any data was entered, validate the input
    if ($action == 'kick') {
        $success = validate_form([], $form);
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
