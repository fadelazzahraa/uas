<?php
function getAllProduct()
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT * FROM product');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [true, $data == false ? 'No products found!' : 'Products found!', $data == false ? [] : $data];
    } catch (Exception $ex) {
        return [false, $ex->getMessage(), null];
    }
}
function getProductByID($id)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
        $stmt->execute([$id]);
        $datum = $stmt->fetch(PDO::FETCH_ASSOC);

        return [true, $datum == false ? 'No product found!' : 'Product found!', $datum == false ? [] : $datum];
    } catch (Exception $ex) {
        return [false, $ex->getMessage(), null];
    }
}
function addProduct($name, $description, $price, $qty, $newimagepath)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('INSERT INTO product (name, description, price, qty, imagepath) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $description, $price, $qty, $newimagepath]);
        return [true, 'Add product success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}
function editProduct($name, $description, $price, $qty, $imagepath, $id)
{
    try {
        $pdo = pdo_connect();
        if ($name != null) {
            $stmt = $pdo->prepare('UPDATE product SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
        }
        if ($description != null) {
            $stmt = $pdo->prepare('UPDATE product SET description = ? WHERE id = ?');
            $stmt->execute([$description, $id]);
        }
        if ($price != null) {
            $stmt = $pdo->prepare('UPDATE product SET price = ? WHERE id = ?');
            $stmt->execute([$price, $id]);
        }
        if ($qty != null) {
            $stmt = $pdo->prepare('UPDATE product SET qty = ? WHERE id = ?');
            $stmt->execute([$qty, $id]);
        }
        if ($imagepath != null) {
            $stmt = $pdo->prepare('UPDATE product SET imagepath = ? WHERE id = ?');
            $stmt->execute([$imagepath, $id]);
        }

        return [true, 'Edit product success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}

function buyProduct($qty, $id)
{
    try {
        $pdo = pdo_connect();
        $stmt = $pdo->prepare('UPDATE product SET qty = qty - ? WHERE id = ? AND qty >= ?');
        $stmt->execute([$qty, $id, $qty]);

        return [true, 'Buy product success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}

function deleteProduct($id)
{
    try {
        $datum = getProductByID($id);
        if ($datum[0] == true) {
            if ($datum[2] == []) {
                return [false, "Error. Product doesn't exist!"];
            }
        } else {
            return [false, $datum[1]];
        }

        $deletestatus = deleteProductImage($datum[2]['imagepath']);

        if ($deletestatus[0] == false) {
            return [false, $deletestatus[1]];
        }

        $pdo = pdo_connect();
        $stmt = $pdo->prepare('DELETE FROM product WHERE id = ?');
        $stmt->execute([$id]);

        return [true, 'Delete product success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}

function uploadProductImage($files)
{
    try {
        $file_name = time() . '-' . $files['name'];
        $file_location = $files['tmp_name'];

        if (getimagesize($file_location) == false) {
            return [false, 'Error. File is not an image!'];
        }

        move_uploaded_file($file_location, '../../image/' . $file_name);

        return [true, 'Upload image success!', $file_name];
    } catch (Exception $ex) {
        return [false, $ex->getMessage(), null];
    }
}
function deleteProductImage($path)
{
    try {
        unlink("../../image/" . $path);

        return [true, 'Delete image success!'];
    } catch (Exception $ex) {
        return [false, $ex->getMessage()];
    }
}


?>