<?php
session_start();

include 'util/cart.php';
include 'util/request.php';
include 'template/header.php';
include 'template/footer.php';

$result = [
  "status" => false,
  "message" => null,
];

if (!empty($_POST)) {
  if (isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
      if (!isset($_GET['id'])) {
        header("location:store.php");
      }
      $id = $_GET['id'];
      $name = isset($_POST['name']) ? $_POST['name'] : null;
      $description = isset($_POST['description']) ? $_POST['description'] : null;
      $qty = isset($_POST['qty']) ? $_POST['qty'] : null;
      $price = isset($_POST['price']) ? $_POST['price'] : null;
      $image = (isset($_FILES['imgupload']) && $_FILES['imgupload']['name'] != "") ? $_FILES['imgupload'] : null;

      if (
        !(
          (is_string($name) || $name == null) &&
          (is_string($description) || $description == null) &&
          (is_numeric($qty) || $name == null) &&
          (is_numeric($price) || $price == null)
        )
      ) {
        $result['status'] = false;
        $result['message'] = 'Edit product failed. Make sure you input data with correct format!';
      } else {
        $result = postRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          $image != null
          ? array(
            "func" => "update",
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "qty" => $qty,
            "price" => $price,
            "img" => curl_file_create($image['tmp_name'], $image['type'], $image['name']),
          )
          : array(
            "func" => "update",
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "qty" => $qty,
            "price" => $price,
          )
        );

        $result2 = getRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          array(
            "func" => "getAll",
          )
        );

        if ($result2['status'] == true) {
          $_SESSION['cart'] = initCart($result2['data']);
        }

        $result3 = getRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          array(
            "func" => "get",
            "id" => $id,
          )
        );

        if ($result3['status'] == false) {
          header("location:store.php");
        } else if ($result3['data'] == []) {
          header("location:store.php");
        } else {
          $product = $result3['data'];
        }

      }
    } else if ($_POST['action'] == 'delete') {
      if (!isset($_GET['id'])) {
        header("location:store.php");
      }

      $result = getRequest(
        'http://localhost:8068/web/uas/api/router/product.router.php',
        array(
          "func" => "delete",
          "id" => $_GET['id'],
        )
      );

      if ($result['status'] == true) {
        $result2 = getRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          array(
            "func" => "getAll",
          )
        );

        if ($result2['status'] == true) {
          $_SESSION['cart'] = initCart($result2['data']);
        }

        header("location:store.php");
      } else {
        $result2 = getRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          array(
            "func" => "get",
            "id" => $id,
          )
        );

        if ($result2['status'] == false) {
          header("location:store.php");
        } else if ($result2['data'] == []) {
          header("location:store.php");
        } else {
          $product = $result2['data'];
        }
      }

    } else {
      header("location:store.php");
    }
  } else {
    header("location:store.php");
  }


} else if (!empty($_GET)) {
  if (isset($_GET['id'])) {
    $result = getRequest(
      'http://localhost:8068/web/uas/api/router/product.router.php',
      array(
        "func" => "get",
        "id" => $_GET['id'],
      )
    );

    if ($result['status'] == false) {
      header("location:store.php");
    } else if ($result['data'] == []) {
      header("location:store.php");
    } else {
      $product = $result['data'];
      $result['message'] = null;
    }
  } else {
    header("location:store.php");
  }
} else {
  header("location:store.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


  <link rel="icon" href="image/favicon.ico" type="image/x-icon">
  <title>
    <?= $product['name'] ?> | Sanapati Food Store
  </title>
</head>

<body>


  <?php
  generateHeader('store');
  ?>

  <div class="container p-5 my-5">
    <div class="row">
      <?php
      if ($result['message'] == null) {
        echo '<p class="text-success text-center">Edit product below</p>';
      } else {
        if ($result['status'] == true) {
          echo '<p class="text-success text-center">' . $result['message'] . '</p>';
        } else {
          echo '<p class="text-danger text-center">' . $result['message'] . '</p>';

        }
      }
      ?>
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="ratio ratio-1x1">
            <img src="image/<?= $product['imagepath'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
          </div>
        </div>
        <form class="" method="post" action="productedit.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
          <input name="imgupload" id="imgupload" class="form-control form-control mt-2" type="file"
            accept="image/jpeg,image/png">
          <label class="text-center mb-3" style="font-size:13px;">Make sure to upload jpeg/png image with 1:1
            ratio</label>
      </div>
      <div class="col-lg-8 col-md-6">
        <input name="name" class="form-control mb-2" placeholder="Name" value="<?= $product['name'] ?>" type="text"
          required>
        <textarea name="description" class="form-control my-2" placeholder="Description"
          required><?= $product['description'] ?></textarea>
        <input name="price" class="form-control my-2" placeholder="Price" value="<?= $product['price'] ?>" type="number"
          required>
        <input name="qty" class="form-control mt-2 mb-4" placeholder="Qty" value="<?= $product['qty'] ?>" type="number"
          required>
        <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal"
          data-bs-target="#confirm-edit">Edit product</button>
        <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal"
          data-bs-target="#confirm-delete">Delete product</button>

        <a href="store.php" class="btn btn-dark">Return to Store</a>
        <!-- Modal -->
        <div class="modal fade" id="confirm-edit" tabindex="-1" role="dialog" aria-labelledby="confirmModal"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <div class="d-flex justify-content-center my-2">
                  <h1 class="text-light">🥗 ✏ ❓</h1>
                </div>
                <div class="d-flex justify-content-center my-0">
                  <h5 class="text-light">
                    Are you sure to edit this product?
                  </h5>
                </div>
                <div class="d-flex justify-content-center my-2">
                  <button type="button" class="btn btn-danger mx-1" data-bs-dismiss="modal">No, cancel it</button>
                  <button type="submit" name="action" value="update" class="btn btn-success mx-1">Yes, sure!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <div class="d-flex justify-content-center my-2">
                  <h1 class="text-danger">🥗 🗑 ❓</h1>
                </div>
                <div class="d-flex justify-content-center my-0">
                  <h5 class="text-light">
                    Are you sure to delete this product?
                  </h5>
                </div>
                <div class="d-flex justify-content-center my-2">
                  <button type="button" class="btn btn-danger mx-1" data-bs-dismiss="modal">No, cancel it</button>
                  <form action="productedit.php?id=<?= $product['id'] ?>" method="post" enctype="multipart/form-data">
                    <button type="submit" name="action" value="delete" class="btn btn-outline-danger ">Yes,
                      delete and
                      return
                      to
                      Store</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal -->
        </form>

      </div>
    </div>

  </div>

  <!-- End of Jumbotron -->

  <?php
  if (isCartNotEmpty($_SESSION['cart'])) {

    echo '<div class="position-fixed position-absolute bottom-0 end-0 m-3">';
    echo '<div class="card text-end" style="width: 10rem;">';
    echo '<a href="cart.php" class="btn btn-primary">🛒 Show cart (' . countCartItem($_SESSION['cart']) . ')</a>';
    echo '</div>';
    echo '</div>';
  }
  ?>

  <!-- Footer -->
  <?php
  generateFooter();
  ?>
  <!-- End of Footer -->

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
    crossorigin="anonymous"></script>
  <!-- End of Script -->
</body>

</html>