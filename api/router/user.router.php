<?php
require "../dbconnect.php";
require "../controller/user.controller.php";
header('Content-Type: application/json');

$pdo = pdo_connect();

if ($pdo == null) {
    echo json_encode([
        "status" => false,
        "data" => "Internal Error"
    ]);
}

if (isset($_GET['func'])) {
    switch ($_GET['func']) {
        default:
            echo json_encode([
                "status" => false,
                "message" => "Invalid Request"
            ]);
            break;
        case "getAll":
            $data = getAllUser();
            echo json_encode([
                "status" => $data[0],
                "data" => $data[1]
            ]);
            break;
        case "get":
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            } else if ($_GET['id'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. ID can't be null!"
                ]);
                break;
            }

            $datum = getUserByID($_GET['id']);
            echo json_encode([
                "status" => $datum[0],
                "data" => $datum[1]
            ]);
            break;
        case "delete":
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            } else if ($_GET['id'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. ID can't be null!"
                ]);
                break;
            }

            $datum = deleteUser($_GET['id']);
            echo json_encode([
                "status" => $datum[0],
                "data" => $datum[1]
            ]);
            break;
    }
} else if (isset($_POST['func'])) {
    switch ($_POST['func']) {
        default:
            echo json_encode([
                "status" => false,
                "message" => "Invalid Request"
            ]);
            break;

        case "create":
            if (!isset($_POST['name'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Name must be provided!"
                ]);
                break;
            } else if ($_POST['name'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Name can't be null!"
                ]);
                break;
            }

            if (!isset($_POST['username'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Username must be provided!"
                ]);
                break;
            } else if ($_POST['username'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Username can't be null!"
                ]);
                break;
            }

            if (!isset($_POST['password'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Password must be provided!"
                ]);
                break;
            }

            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = $_POST['password'] != null ? $_POST['password'] : "";

            $status = addUser($_POST['name'], $_POST['username'], $_POST['password']);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
            ]);
            break;
        case "update":
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            } else if ($_POST['id'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. ID can't be null!"
                ]);
                break;
            }

            $id = $_POST['id'];
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $username = isset($_POST['username']) ? $_POST['username'] : null;

            $status = editUser($name, $username, $id);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
            ]);
            break;
        case "changepassword":
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            } else if ($_POST['id'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. ID can't be null!"
                ]);
                break;
            }

            if (!isset($_POST['oldpassword'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Password must be provided!"
                ]);
                break;
            }
            if (!isset($_POST['newpassword'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Password must be provided!"
                ]);
                break;
            }

            $id = $_POST['id'];
            $oldpassword = $_POST['oldpassword'] != null ? $_POST['oldpassword'] : '';
            $newpassword = $_POST['newpassword'] != null ? $_POST['newpassword'] : '';

            $status = changePasswordUser($oldpassword, $newpassword, $id);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
            ]);
            break;
        case "switchrole":
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            } else if ($_POST['id'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. ID can't be null!"
                ]);
                break;
            }

            $status = switchRoleUser($_POST['id']);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
            ]);
            break;

    }
}