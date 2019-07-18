<?php
// Uzkraunam visus reikalingus failus
require '../config.php';
require ROOT . '/functions/file.php';
require ROOT . '/functions/html/builder.php';
require ROOT . '/functions/form/core.php';

session_start();
$form = [
    'attr' => [
        //'action' => '', Nebūtina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [

        'passwordas' => [
            'label' => 'Slaptazodis',
            'type' => 'password',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Password'
                ],
                'validators' => [
                    'validate_not_empty',
                ]
            ],
        ],
        'mail' => [
            'label' => 'Mailas',
            'type' => 'email',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Tavo mailas'
                ],
                'validators' => [
                    'validate_not_empty',
                ]
            ],
        ],
    ],
    'buttons' => [
        'create' => [
            'title' => 'Login',
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
    ],
    'validators' => [
        'validate_login'
    ]
];


function form_fail($filtered_input, &$form)
{
    var_dump('Form failed!');
}

function validate_login($filtered_input, &$fields, &$form)
{
    $users_array = file_to_array(STORAGE_FILE);
    if ($users_array) {
        foreach ($users_array as $user_key => $user_value) {
            if ($user_value['email'] === $filtered_input['mail'] && $user_value['pass'] !== $filtered_input['passwordas']) {
                $fields['passwordas']['error'] = 'Patikrinti slaptazodi!';
                break;
            } else if
            ($user_value['email'] === $filtered_input['mail'] && $user_value['pass'] === $filtered_input['passwordas']) {
                $fields['mail']['error'] = 'Prisiloginai!';
                return true;
                break;
            } else if ($user_value['email'] !== $filtered_input['mail']) {
                $fields['mail']['error'] = 'Patikrink maila arba uzpildyk registracija!';
            }
        }
    }
    return false;
}

function form_success($filtered_input, &$form)
{
    $_SESSION = $filtered_input;
    var_dump($_SESSION);
}


// Get all data from $_POST
$input = get_form_input($form);

// If any data was entered, validate the input
if (!empty($input)) {
    $success = validate_form($input, $form);
//    $message = $success ? 'Nauja komanda sukurta' : 'Klaida!';
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
