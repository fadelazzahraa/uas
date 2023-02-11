<?php
function getAllUser()
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT * FROM user');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [true, $data == false ? null : $data];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function getUserByID($id)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute([$id]);
        $datum = $stmt->fetch(PDO::FETCH_ASSOC);

        return [true, $datum == false ? null : $datum];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function addUser($name, $username, $password)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('INSERT INTO user (name, username, password, role) VALUES (?, ?, ?, "user")');
        $stmt->execute([$name, $username, $password]);
        return [true, 'Add user success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function editUser($name, $username, $id)
{
    try {
        $pdo = pdo_connect();
        if ($name != null) {
            $stmt = $pdo->prepare('UPDATE user SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
        }
        if ($username != null) {
            $stmt = $pdo->prepare('UPDATE user SET username = ? WHERE id = ?');
            $stmt->execute([$username, $id]);
        }

        return [true, 'Edit user success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function changePasswordUser($oldpassword, $newpassword, $id)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT username FROM user WHERE id = ? AND password = ?');
        $stmt->execute([$id, $oldpassword]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $stmt = $pdo->prepare('UPDATE user SET password = ? WHERE id = ?');
            $stmt->execute([$newpassword, $id]);
        } else {
            return [false, 'Error. ID and/or password mismatch'];
        }
        return [true, 'Change user password success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}

function switchRoleUser($id)
{
    try {
        $pdo = pdo_connect();
        $user = fetchUser($id);
        if ($user[0] == true) {
            if ($user[1] != null) {
                if ($user['role'] == 'admin') {
                    $stmt = $pdo->prepare('UPDATE user SET role = "user" WHERE id = ?');
                } else {
                    $stmt = $pdo->prepare('UPDATE user SET role = "admin" WHERE id = ?');
                }
                $stmt->execute([$id]);
            } else {
                return [false, 'Error. User not found'];
            }
        } else {
            return [false, $user[1]];
        }
        return [true, 'Switch role success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function deleteUser($id)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('DELETE FROM user WHERE id = ?');
        $stmt->execute([$id]);

        return [true, 'Delete user success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}

?>