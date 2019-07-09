<?php

$array = [
    [
        'name' => 'Zalgiris',
        'players' => [
            ['name' => 'Martynas',
                'age' => 23],
        ],
        'wins' => 130,
        'losses' => 23,
    ],
    [
        'name' => 'Rytas',
        'players' => [
            ['name' => 'Davis',
                'age' => 55],
        ],
        'wins' => 120,
        'losses' => 43,
    ],
    [
        'name' => 'Zeusas',
        'players' => [
            ['name' => 'Pepe',
                'age' => 32],
        ],
        'wins' => 160,
        'losses' => 2,
    ],
    [
        'name' => 'ABS',
        'players' => [
            ['name' => 'Manpise',
                'age' => 43],
        ],
        'wins' => 34,
        'losses' => 54,
    ]
];

$array[0]['wins'] = rand(0, 120);


$json = json_encode($array);
file_put_contents('info.txt', $json);
$encoded_string = file_get_contents('info.txt');
$decoded = json_decode($encoded_string, true);
$teams[0]['players'][] = ['name' => 'kiausas', 'age' => rand(0, 57)];
var_dump($teams[0]['players']);
$jsonas = json_encode($teams);
file_put_contents('info.txt', $jsonas)


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
<table>
    <tr>
        <th>Name:</th>
        <th>Age:</th>
        <th>Wins:</th>
        <th>Losses:</th>
    </tr>
    <?php foreach ($decoded as $team_id => $team): ?>
        <?php foreach ($team['players'] as $player): ?>
            <tr>
                <td><?php print $player['name']; ?></td>
                <td><?php print $player['age']; ?></td>
                <td><?php print $team['wins']; ?></td>
                <td><?php print $team['losses']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>

</body>
</html>
