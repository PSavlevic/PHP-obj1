<?php
// Uzkraunam visus reikalingus failus
require '../config.php';
require ROOT . '/functions/file.php';
require ROOT . '/functions/html/builder.php';
require ROOT . '/functions/form/core.php';


$teams = file_to_array(STORAGE_FILE);
if(!$teams) {
    $teams = [];
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

        <?php foreach ($teams as $team_index => $team): ?>
            <h2>
                <?php print "{$team['team_name']}: {$team['score']}"; ?>
            </h2>
            <table>
                <thead>
                    <tr>                   
                        <th>
                            Player name
                        </th>
                        <th>
                            Player score
                        </th>
                    </tr>
                </thead> 
                <tbody>
                    <?php foreach ($team['players'] as $player): ?>
                        <tr>  
                            <td>
                                <?php print $player['name']; ?>
                            </td>
                            <td>
                                <?php print $player['score']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </body>
</html>