<?php

function alaus() {
    return print "salto alaus bokalas,";
}

function duona(){
    return print "kepta duona su suriu";
}

$array = [
        "alaus",
        "duona"
];

foreach ($array as $funkcijos_pavadinimas){
    $funkcijos_pavadinimas();
}


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


</body>
</html>