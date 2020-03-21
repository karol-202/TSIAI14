<?php

function badRequest() {
    http_response_code(400);
    echo 'Bad request';
    exit();
}

function redirectToIndex() {
    header("Location: index.php");
    exit();
}
