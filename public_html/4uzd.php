<?php
// Uzkraunam visus reikalingus failus
require '../config.php';
require ROOT . '/functions/file.php';
require ROOT . '/functions/html/builder.php';
require ROOT . '/functions/form/core.php';
session_start();

var_dump('$_SESSION', $_SESSION);

function get_users() {
    $user_from_file = file_to_array(STORAGE_FILE);
    $user_mail_from_cookie = $_SESSION['mail'] ?? '';
        foreach ($user_from_file as $user_key => $user_value) {
            if($user_value['email'] == $user_mail_from_cookie) {
                return $user_value['first'];
        }
    }
}

$user = get_users();

// If any data was entered, validate the input
if ($user) {
    $user_name = $user;
    $message = "Hello, $user_name";
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

</body>
</html>
