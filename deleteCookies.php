
<?php
$past = time() - 3600;
foreach ($_COOKIE as $key => $value) {
    setcookie($key, $value, $past, '/');
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>All Cookies Deleted</h1>
    </body>
</html>
