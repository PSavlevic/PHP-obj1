<?php
// Uzkraunam visus reikalingus failus
require '../config.php';
require ROOT . '/functions/file.php';
require ROOT . '/functions/html/builder.php';
require ROOT . '/functions/form/core.php';

$komandu_array = get_team_names();

$form = [
    'attr' => [
        //'action' => '', Nebūtina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [],
    'buttons' => [
        'kick' => [
            'title' => 'Kick',
            'extra' => [
                'attr' => [
                    'class' => 'red-btn'
                ]
            ]
        ],
    ],
    'validators' => [
        'validate_player'
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

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

function validate_player($filtered_input, &$fields, &$form) {
    $teams = file_to_array(STORAGE_FILE);
    if ($teams) {
        $player_data = json_decode($_COOKIE[PLAYER_COOKIE], true);
        $player_team_index = $player_data['team_index'];
        $player_index = $player_data['player_index'];
        $team = &$teams[$player_team_index] ?? false;

        if ($team) {
            $player = &$team['players'][$player_index] ?? false;

            if ($player) {
                return true;
            }
        }
    }
    
    $form['message'] = 'Pisk nx hackeri';    
}

function form_success($filtered_input, &$form) {
    $teams = file_to_array(STORAGE_FILE);
    if ($teams) {
        $decoded_player_array = json_decode($_COOKIE[PLAYER_COOKIE], true);
        $player_team_index = $decoded_player_array['team_index'];
        $player_index = $decoded_player_array['player_index'];
        $team = &$teams[$player_team_index] ?? false;

        if ($team) {
            $player = &$team['players'][$player_index] ?? false;

            if ($player) {
                $team['score'] += 1;
                $player['score'] += 1;
                array_to_file($teams, STORAGE_FILE);
            }
        }
    }

//    $_COOKIE[PLAYER_COOKIE] = $player_data;
//    header('Location: /play.php');
}

if (!empty($_COOKIE[PLAYER_COOKIE])) {

// Get all data from $_POST
    $action = get_form_action($form);

// If any data was entered, validate the input
    if (!empty($action)) {
        $success = validate_form([], $form);
        $message = $success ? 'Ispirei!' : 'Klaida!';
    }
} else {
    $form = [];
    $message = 'Tu neprikausai komandai!';
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
            <!--            <div class="message">
                            <span class="text"><?php print $message; ?></span>
                            <span class="close">X</span>
                        </div>-->
        <?php endif; ?>

        <!-- $form HTML generator -->
        <?php require '../templates/form.tpl.php'; ?>
    </body>
</html>
