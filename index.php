<?php
require_once 'common.php';

if(!isset($_GET)) badRequest();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>TSIAI14 - PDO</title>
</head>
<body>
    <h1>TSIAI 14</h1>
    <div class="main">
        <?php
        if($action == 'read') include 'userRead.php';
        else if($action == 'add') include 'userAdd.php';
        else if($action == 'edit') include "userEdit.php";
        ?>
    </div>
</body>
</html>
