<?php
require_once 'common.php';
require_once 'userDB.php';

$userId = $_GET['user-id'] ?: $_POST['user-id'] ?: badRequest();
$user = getUserFromDb();

function getUserFromDb() {
    return executeOnDb(function ($db) {
        global $userId;
        $query = $db->prepare('SELECT * FROM players WHERE id = :userId');
        $query->bindValue('userId', $userId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch();
    });
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    editUser();
    redirectToIndex();
}

function editUser() {
    global $userId;
    $firstName = $_POST['first-name'] ?: badRequest();
    $lastName = $_POST['last-name'] ?: badRequest();
    $birthDate = $_POST['birth-date'] ?: badRequest();
    saveUserToDb($userId, $firstName, $lastName, $birthDate);
}

function saveUserToDb($userId, $firstName, $lastName, $birthDate) {
    return executeOnDb(function ($db) use ($userId, $firstName, $lastName, $birthDate) {
        $query = $db->prepare('UPDATE players SET first_name = :firstName, last_name = :lastName, birth_date = :birthDate WHERE id = :userId');
        $query->bindValue('userId', $userId, PDO::PARAM_INT);
        $query->bindValue('firstName', $firstName, PDO::PARAM_STR);
        $query->bindValue('lastName', $lastName, PDO::PARAM_STR);
        $query->bindValue('birthDate', $birthDate, PDO::PARAM_STR);
        return $query->execute();
    });
}
?>

<form action="userEdit.php" method="post">
    <input type="hidden" name="user-id" value="<?php echo $userId ?>">
    
    <label for="input-first-name">Imie: </label>
    <input type="text" id="input-first-name" name="first-name" value="<?php echo $user['first_name'] ?>">
    <br>

    <label for="input-last-name">Nazwisko: </label>
    <input type="text" id="input-last-name" name="last-name" value="<?php echo $user['last_name'] ?>">
    <br>

    <label for="input-birth-date">Data urodzenia</label>
    <input type="date" id="input-birth-date" name="birth-date" value="<?php echo $user['birth_date'] ?>">
    <br>

    <input type="submit" value="Zapisz">
</form>