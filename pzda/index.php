 <?php
// Uzkraunam visus reikalingus failus
require 'config.php';
require 'functions/file.php';
require 'functions/html/builder.php';
require 'functions/form/core.php';

$nav = [
    [
        'url' => '/',
        'title' => 'Home'
    ]
];

$form = [
    'attr' => [
        //'action' => '', Neb8tina, jeigu action yra ''
        'method' => 'POST',
    ],
    'fields' => [
        'team_name' => [
            'label' => 'Enter your team',
            'type' => 'text',
            'extra' => [
                'attr' => [
                    'class' => 'my-test-field',
                    'placeholder' => 'Team name'
                ],
                'validators' => [
                    'validate_not_empty',
                    'validate_team_name'
                ]
            ],
        ],
//        'test_select' => [
//            'type' => 'select',
//            'label' => 'It`s Time To Choose',
//            'value' => 1, // Koreliuoja su options pasirinkimo indeksu
//            'options' => [
//                'Grybai',
//                'Pauksciai',
//                'Lavonai'
//            ],
//            'extra' => [
//                'attr' => [
//                    'class' => 'my-select-field',
//                ],
//                'validators' => [
//                    'validate_not_empty'
//                ]
//            ]
//        ]
    ],
    'buttons' => [
        'create' => [
            'title' => 'Prideti',
            'extra' => [
                'attr' => [
                    'class' => 'blue-btn'
                ]
            ]
        ],
//        'delete' => [
//            'title' => 'NO',
//            'extra' => [
//                'attr' => [
//                    'class' => 'red-btn'
//                ]
//            ]
//        ]
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

function form_fail($filtered_input, &$form) {
    var_dump('Form failed!');
}

function form_success($filtered_input, &$form) {
    $new_data = [];
    $old_data = file_to_array('STORAGE_DATA');
    if (!empty($old_data)) {
        $new_data = $old_data;
    }
    $new_data[] = $filtered_input;
    array_to_file($new_data, 'STORAGE_DATA');
    var_dump('Form succeeded!');
}

// Get all data from $_POST
$input = get_form_input($form);

// If any data was entered, validate the input
if (!empty($input)) {
    $success = validate_form($input, $form);
    $message = $success ? 'Cool!' : 'Not Cool!';
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
