<?php

if (isset($_COOKIE["clientId"])) {
    echo "<h1>Client id=" . $_COOKIE["clientId"] . "</h1>";
} else {

    echo "<h1>No client id exists for this browser</h1>";
}