<?php

require 'form.php';

function validate_team_name($field_input, &$field) {
    if (file_exists('info.txt')) {
        $teams = file_to_array('info.txt');
        if (!empty($teams)) {
            foreach ($teams as $team) {
                if ($team['team_name'] == $field_input) {
                    $field['error'] = 'Team already exists!';
                    return null;
                } else {
                    $field['error'] = 'Team added!';
                }
            }
        }
    }
    return true;
}

function form_success($filtered_inputs, &$form) {
    $new_data = [];
    $old_data = file_to_array('info.txt');
    if (!empty($old_data)) {
        $new_data = $old_data;
    }
    $new_data[] = $filtered_inputs;
    array_to_file($new_data, 'info.txt');
}

function form_fail($filtered_input, &$form) {
    print 'Failed to save info to file...';
}

$form = [
    'action' => '',
    'method' => 'POST',
    'fields' => [
        'team_name' => [
            'label' => 'Team Name',
            'type' => 'text',
            'value' => '',
            'validators' => [
                'validate_not_empty',
                'validate_team_name'
            ],
            'placeholder' => 'Enter team!'
        ],
    ],
    'buttons' => [
        'submit' => [
            'type' => 'button',
            'title' => 'Create',
        ],
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

$filtered_inputs = get_form_inputs($form);

switch (get_form_action()) {
    case 'submit':
        $success = validate_form($filtered_inputs, $form);
        break;
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP form</title>
    <link rel="stylesheet" type="text/css" href="css/normalise.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<nav>
    <a href="index.php"><span>Index</span></a>
    <a href="create.php"><span>Create</span></a>
    <a href="join.php"><span>Join Team</span></a>
    <a href="play.php"><span>Play</span></a>
    <a href="scoreboard.php"><span>Scoreboard</span></a>
</nav>
<form class="form-top" action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <?php foreach ($form['fields'] as $key => $input): ?>
        <?php if (isset($input['label'])): ?>
            <label for="<?php print $key; ?>"><?php print $input['label']; ?></label>
        <?php endif; ?>
        <input name="<?php print $key; ?>"
               type="<?php print $input['type']; ?>"
               placeholder="<?php print $input['placeholder']; ?>"
               value="<?php print $input['value']; ?>"
               id="<?php print $key; ?>">
        <?php if (isset($input['error'])): ?>
            <span class="error"><?php print $input['error']; ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php foreach ($form['buttons'] as $button_key => $button): ?>
        <button name="action" value="<?php print $button_key; ?>">
            <?php print $button['title']; ?>
        </button>
    <?php endforeach; ?>
</form>
</body>
</html>