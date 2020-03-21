<?php
require_once 'common.php';
require_once 'userDB.php';

function showUsers($cellsFunctions) {
    foreach (readUsers() as $user) {
        echo '<tr>';
        foreach ($cellsFunctions as $cellFunction) {
            echo '<td>'.$cellFunction($user).'</td>';
        }
        echo '</tr>';
    }
}

function readUsers() {
    $minAge = isset($_GET['min-age']) ? $_GET['min-age'] : null;
    $maxAge = isset($_GET['max-age']) ? $_GET['max-age'] : null;

    $allUsers = readUsersFromDB();
    $filteredUsers = array();
    foreach ($allUsers as $row) {
        if(filterUser($row, $minAge, $maxAge)) array_push($filteredUsers, $row);
    }
    return $filteredUsers;
}

function readUsersFromDB() {
    return executeOnDb(function ($db) {
        return $db->query('SELECT * FROM players');
    });
}

// Filtrowanie dokonywane poza zapytaniem SQL ze względu na ograniczone możliwości operowania na datach przez SQLite
function filterUser($user, $minAge, $maxAge) {
    $age = getUserAge($user);
    if($minAge != null && $age < $minAge) return false;
    if($maxAge != null && $age > $maxAge) return false;
    return true;
}

function getUserAge($user) {
    $birth_date_string = $user['birth_date'];
    $birth_date = date_create($birth_date_string);
    $diff = date_diff($birth_date, date_create());
    $years = $diff->y;
    if($diff->invert) $years = -$years;
    return $years;
}

$userId = fn($user) => $user['id'];
$userFirstName = fn($user) => $user['first_name'];
$userLastName = fn($user) => $user['last_name'];
$userBirthDate = fn($user) => $user['birth_date'];
$userAge = fn($user) => getUserAge($user);
$userActions = fn($user) => "
<form action='index.php' method='get'>
    <input type='submit' value='Edytuj'>
    <input type='hidden' name='action' value='edit'>
    <input type='hidden' name='user-id' value='$user[0]'>
</form>
<form action='userDelete.php' method='post'>
    <input type='submit' value='Usuń'>
    <input type='hidden' name='user-id' value='$user[0]'>
</form>
";
?>

<form action="index.php" method="get">
    <label for="input-min-age">Wiek: </label>
    <input type="number" id="input-min-age" name="min-age">
    <label for="input-max-age"> - </label>
    <input type="number" id="input-max-age" name="max-age">
    <br>

    <input type="submit" value="Pokaż dane">
</form>
<table class="data">
    <thead>
    <tr>
        <td>ID</td>
        <td>Imie</td>
        <td>Nazwisko</td>
        <td>Data urodzenia</td>
        <td>Wiek</td>
        <td>Akcje</td>
    </tr>
    </thead>
    <tbody>
    <?php showUsers(array($userId, $userFirstName, $userLastName, $userBirthDate, $userAge, $userActions)); ?>
    </tbody>
</table>
<form action="index.php" method="get">
    <input type="hidden" name="action" value="add">
    <input type="submit" value="Nowy użytkownik">
</form>
