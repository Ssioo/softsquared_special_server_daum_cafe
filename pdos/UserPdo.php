<?php

function isSuccessSignUp($email, $pw, $name)
{
    /* Validation */
    if ($email == null) {
        return 301;
    } else if (strlen($email) == 0 || strlen($email) > 50) {
        return 302;
    }
    if ($pw == null) {
        return 304;
    } else if (strlen($pw) < 6 || strlen($pw) > 20) {
        return 305;
    }
    if ($name == null) {
        return 306;
    } else if (strlen($name) > 20) {
        return 307;
    }

    /* DB CHECK */
    $pdo = pdoSqlConnect();
    $query = "SELECT * FROM user WHERE email = ? AND status = 'ACTIVE';";

    $st = $pdo->prepare($query);
    $st->execute([$email]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    if (count($res) != 0) {
        return 308;
    }

    $st = null;
    $pdo = null;

    $pdo = pdoSqlConnect();
    $query = "SELECT * FROM user WHERE name = ? AND status = 'ACTIVE';";

    $st = $pdo->prepare($query);
    $st->execute([$name]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    if (count($res) != 0) {
        return 309;
    }

    $st = null;
    $pdo = null;

    /* DB INSERT */
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO user (email, password, name, status) value (?, ?, ?, DEFAULT);";

    $st = $pdo->prepare($query);
    $st->execute([$email, $pw, $name]);

    return 200;
}

function getUserInfo($email) {
    $pdo = pdoSqlConnect();
    $query = "SELECT * FROM user WHERE email = ? AND status = 'ACTIVE';";

    $st = $pdo->prepare($query);
    $st->execute([$email]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    return $res[0];
}

function resignUser($email) {
    $pdo = pdoSqlConnect();
    $query = "UPDATE user SET status = 'DELETED' WHERE email = ? AND status = 'ACTIVE';";

    $st = $pdo->prepare($query);
    $st->execute([$email]);

    return TRUE;
}