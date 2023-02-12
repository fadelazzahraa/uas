<?php
require "../dbconnect.php";
require "../controller/product.controller.php";
header('Content-Type: application/json');

$pdo = pdo_connect();

if ($pdo == null) {
    echo json_encode([
        "status" => false,
        "message" => "Internal Error"
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
            $data = getAllProduct();
            echo json_encode([
                "status" => $data[0],
                "message" => $data[1],
                "data" => $data[2]
            ]);
            break;
        case "get":
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            }

            $datum = getProductByID($_GET['id']);
            echo json_encode([
                "status" => $datum[0],
                "message" => $datum[1],
                "data" => $datum[2]
            ]);
            break;
        case "delete":
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            }

            $status = deleteProduct($_GET['id']);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
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

            if (!isset($_POST['description'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Description must be provided!"
                ]);
                break;
            } else if ($_POST['description'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Description can't be null!"
                ]);
                break;
            }

            if (!isset($_POST['qty'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Qty must be provided!"
                ]);
                break;
            } else if ($_POST['qty'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Qty can't be null!"
                ]);
                break;
            }

            if (!isset($_POST['price'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Price must be provided!"
                ]);
                break;
            } else if ($_POST['price'] == null) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Price can't be null!"
                ]);
                break;
            }

            if (!isset($_FILES['img'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Image must be provided!"
                ]);
                break;
            } else if ($_FILES['img']['name'] == "") {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Image can't be null!"
                ]);
                break;
            }

            $name = $_POST['name'];
            $description = $_POST['description'];
            $qty = $_POST['qty'];
            $price = $_POST['price'];

            $uploadImage = uploadProductImage($_FILES['img']);
            if ($uploadImage[0] == false) {
                echo json_encode([
                    "status" => false,
                    "message" => $uploadImage[1],
                ]);
                break;
            } else {
                $status = addProduct($name, $description, $price, $qty, $uploadImage[2]);
                echo json_encode([
                    "status" => $status[0],
                    "message" => $status[1]
                ]);
                break;
            }

        case "update":
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            }

            $id = $_POST['id'];
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $description = isset($_POST['description']) ? $_POST['description'] : null;
            $qty = isset($_POST['qty']) ? $_POST['qty'] : null;
            $price = isset($_POST['price']) ? $_POST['price'] : null;

            if (isset($_FILES['img'])) {
                if ($_FILES['img']['name'] == "") {
                    echo json_encode([
                        "status" => false,
                        "message" => "Error. Image can't be null!"
                    ]);
                    break;
                } else {
                    $datum = getProductByID($_POST['id']);
                    if ($datum[0] == false) {
                        echo json_encode([
                            "status" => false,
                            "message" => $datum[1],
                        ]);
                        break;
                    } else {
                        $deletestatus = deleteProductImage($datum[2]['imagepath']);
                        if ($deletestatus[0] == false) {
                            echo json_encode([
                                "status" => false,
                                "message" => $deletestatus[1],
                            ]);
                            break;
                        } else {
                            $uploadImage = uploadProductImage($_FILES['img']);
                            if ($uploadImage[0] == false) {
                                echo json_encode([
                                    "status" => false,
                                    "message" => $uploadImage[1],
                                ]);
                                break;
                            } else {
                                $status = editProduct($name, $description, $price, $qty, $uploadImage[2], $id);
                                echo json_encode([
                                    "status" => $status[0],
                                    "message" => $status[1]
                                ]);
                                break;
                            }
                        }
                    }
                }
            } else {
                $status = editProduct($name, $description, $price, $qty, null, $id);
                echo json_encode([
                    "status" => $status[0],
                    "message" => $status[1]
                ]);
                break;
            }

        case "buy":
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "ID must be provided!"
                ]);
                break;
            }
            if (!isset($_POST['qty'])) {
                echo json_encode([
                    "status" => false,
                    "message" => "Qty must be provided!"
                ]);
                break;
            }
            $datum = getProductByID($_POST['id']);
            if ($datum[0] == false) {
                echo json_encode([
                    "status" => false,
                    "message" => $datum[1],
                ]);
                break;
            } else if ($_POST['qty'] < 0) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error. Qty can't be negative!"
                ]);
                break;
            } else if ($datum[1]['qty'] < $_POST['qty']) {
                echo json_encode([
                    "status" => false,
                    "message" => 'Not enough items available for this. Qty left is ' . $datum[1]['qty']
                ]);
                break;
            }

            $status = buyProduct($_POST['qty'], $_POST['id']);
            echo json_encode([
                "status" => $status[0],
                "message" => $status[1]
            ]);
            break;

    }
}