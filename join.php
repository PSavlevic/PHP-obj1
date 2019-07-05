<?php


?>
<!DOCTYPE html>
<html>
<head>
	<title>JOIN</title>
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
<?php if(count($teams) > 0): ?>
<?php if(isset($_COOKIE['team_name'])): ?>
	<h1>You are already in <?php print $_COOKIE['team_name']; ?> team</h1>
<?php else: ?>
<form method="POST">
	<select name="choose_team">
		<?php foreach ($teams as $key => $team): ?>
			<option value="<?php print $team; ?>"><?php print $team; ?></option>
		<?php endforeach; ?>
	</select>
	<input type="text" name="user" placeholder="Enter your name">
	<input type="submit" name="send">
</form>
<?php endif; ?>
<?php else: ?>
	<h1>Teams 404</h1>
<?php endif; ?>
</body>
</html>