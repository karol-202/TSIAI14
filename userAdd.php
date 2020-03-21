<?php
require_once 'common.php';
require_once 'userDB.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    addUser();
    redirectToIndex();
}

function addUser() {
    $firstName = $_POST['first-name'] ?: badRequest();
    $lastName = $_POST['last-name'] ?: badRequest();
    $birthDate = $_POST['birth-date'] ?: badRequest();
    addUserToDb($firstName, $lastName, $birthDate);
}

function addUserToDb($firstName, $lastName, $birthDate) {
    return executeOnDb(function ($db) use ($firstName, $lastName, $birthDate) {
        $query = $db->prepare('INSERT INTO players (first_name, last_name, birth_date) VALUES (:firstName, :lastName, :birthDate);');
        $query->bindValue('firstName', $firstName, PDO::PARAM_STR);
        $query->bindValue('lastName', $lastName, PDO::PARAM_STR);
        $query->bindValue('birthDate', $birthDate, PDO::PARAM_STR);
        return $query->execute();
    });
}
?>

<form action="userAdd.php" method="post">
    <label for="input-first-name">Imie: </label>
    <input type="text" id="input-first-name" name="first-name">
    <br>

    <label for="input-last-name">Nazwisko: </label>
    <input type="text" id="input-last-name" name="last-name">
    <br>

    <label for="input-birth-date">Data urodzenia</label>
    <input type="date" id="input-birth-date" name="birth-date">
    <br>

    <input type="submit" value="Dodaj">
</form>
