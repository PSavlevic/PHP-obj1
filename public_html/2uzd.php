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
        'first_name' => [
            'label' => 'Name',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Name'
                ],
                'validators' => [
                    'validate_not_empty',
                ]
            ],
        ],
        'last_name' => [
            'label' => 'Last Name',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Last Name'
                ],
                'validators' => [
                    'validate_not_empty',
                ]
            ],
        ],
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
        'passwordas2' => [
            'label' => 'Repeat passworda',
            'type' => 'password',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Repeat password'
                ],
                'validators' => [
                    'validate_not_empty',
                ]
            ],
        ],
        'mail' => [
            'label' => 'Mailas',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'placeholder' => 'Tavo mailas'
                ],
                'validators' => [
                    'validate_not_empty',
                    'validate_mail'
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
    ],
    'validators' => [
        'validate_fields_match' => [
            'passwordas',
            'passwordas2'
        ]
    ]
];

function validate_fields_match(&... $params) {
    var_dump($params);
    $success = true;
    $pass_array = [];
    $field_ids = $params[0];
    $filtered_input = $params[1];
    $fields = &$params[2];

    $first_value = $filtered_input[$field_ids[0]];
    foreach ($field_ids as $field_id) {
        $field_value = $filtered_input[$field_id];
        if ($field_value !== $first_value) {
            $fields[end($field_ids)]['error'] = 'Password\'s didn\'t match...';
            return false;
        }
    }

    return true;
}


function form_fail($filtered_input, &$form) {
//    var_dump('Form failed!');
}

function form_success($filtered_input, &$form)
{
    $team = [
        'first' => $filtered_input['first_name'],
        'last' => $filtered_input['last_name'],
        'email' => $filtered_input['mail'],
        'pass' => $filtered_input['passwordas'],
    ];


    $new_data = [];
    $old_data = file_to_array(STORAGE_FILE);
    if ($old_data) {
        $data = $old_data;
    }
    $data[] = $team;
    array_to_file($data, STORAGE_FILE);
}

function validate_mail($field_input, &$field)
{
    $file_data = file_to_array(STORAGE_FILE);
    if ($file_data) {
        foreach ($file_data as $team_id => $team) {
            if ($field_input == $team['email']) {
                $field['error'] = 'Toks useris jau yra!';
                return false;
            }
        }
    }
    return true;
}

// Get all data from $_POST
$input = get_form_input($form);

// If any data was entered, validate the input
if (!empty($input)) {
    $success = validate_form($input, $form);
    $message = $success ? 'Registracija sekminga' : 'Klaida!';
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

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Last Name</th>
        <th>Mail</th>
        <th>Pass</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($_SESSION)): ?>
        <?php foreach ($_SESSION['teams'] as $key => $value): ?>
            <tr>
                <td>
                    <?php print $value['first']; ?>
                </td>
                <td>
                    <?php print $value['last']; ?>
                </td>
                <td>
                    <?php print $value['email']; ?>
                </td>
                <td>
                    <?php print $value['pass']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>
