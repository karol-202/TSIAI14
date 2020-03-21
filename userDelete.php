<?php
require_once 'common.php';
require_once 'userDB.php';

if(isset($_POST)) {
    deleteUser();
    redirectToIndex();
}

function deleteUser() {
    $userId = isset($_POST['user-id']) ? $_POST['user-id'] : badRequest();
    deleteUserFromDb($userId);
}

function deleteUserFromDb($userId) {
    return executeOnDb(function ($db) use ($userId) {
        $query = $db->prepare('DELETE FROM players WHERE id = :id');
        $query->bindValue('id', $userId, PDO::PARAM_INT);
        return $query->execute();
    });
}
