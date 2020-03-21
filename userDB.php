<?php
function executeOnDb($block) {
    try {
        $db = new PDO('sqlite:jurski.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $block($db);
    }
    catch (PDOException $e) {
        echo "Database error: ".$e->getMessage();
        return null;
    }
}