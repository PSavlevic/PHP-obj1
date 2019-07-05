<?php 

/**
 * Saves array to file
 * @param type array. $array - array we want to convert
 * @param type file. $file_name - file into where we want to save 
 * @return boolean
 */
function array_to_file($array, $file_name) {
    $array_encode_to_jason_string = json_encode($array);
    $success = file_put_contents($file_name, $array_encode_to_jason_string); //$success atiduoda irasyta baitu skaiciu arba false
    if($success !== FALSE) {
        return true;
    } else {
        return false;
    }
}

/**
 * Decoding array from file
 * @param type file. $file_name - file we get data from
 * @return array|boolean
 */
function file_to_array($file_name) {
    if (file_exists($file_name)) {
        $encoded_string = file_get_contents($file_name);
        if ($encoded_string !== false) {
            return json_decode($encoded_string, true);
        } else {
            return false;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>PLAY</title>
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
<?php if(isset($_COOKIE['team_name'])): ?>
<form method="POST">
	<h1>Go <?php //enter team member  ?></h1>
	<input type="submit" name="kick" value="kick that ball">
</form>
<?php else: ?>
	<h1>Team is not selected</h1>
<?php endif; ?>
<?php

$_COOKIE['counter'] = ((isset($_COOKIE['counter'])) ? $_COOKIE['counter'] : setcookie('counter', 0));
if(isset($_POST['kick'])) {
     $_COOKIE['counter']++;
     setcookie('counter', $_COOKIE['counter']);
}

// need to save team name and team score

?>
</body>
</html>