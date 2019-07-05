<?php

$teams = [
    'team_name' => [
        [
            'name' => 'Grybai',
            'members' => [
                'user1',
                'user2',
                'user3',
            ],
            'score' => '5',
        ],
        [
            'name' => 'Mire pauksciai',
            'members' => [
                'user1',
                'user2',
                'user3',
            ],
            'score' => '3',
        ]
    ],
];


function get_data()
{
    if (file_exists('info.txt')) {
        $get_array = file_get_contents('info.txt');
        $decoded_data = json_decode($get_array);
        return $decoded_data;
    }
}

//$komandos = get_data();

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP form</title>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
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
<h1>P2DABALL SCOREBOARD</h1>
<table class="scoreboard_table">
    <thead>
    <th>Name</th>
    <th>Score</th>
    </thead>
    <?php foreach ($teams['team_name'] as $key => $value): ?>
        <?php if (isset($value['score'])): ?>
            <tr>
                <td><?php print $value['name']; ?></td>
                <td><?php print $value['score']; ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
</body>
</html>