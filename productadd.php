<?php
session_start();

include 'util/cart.php';
include 'util/request.php';
include 'template/header.php';
include 'template/footer.php';

$result = [
  "status" => False,
  "message" => null,
];

if (!empty($_POST)) {
  if (isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
      if (
        !(
          isset($_POST['name']) &&
          isset($_POST['description']) &&
          isset($_POST['qty']) &&
          isset($_POST['price']) &&
          isset($_FILES['imgupload']) && $_FILES['imgupload']['name'] != ""
        )
      ) {
        $result['message'] = 'Add product failed. Make sure you input all data!';
      } else if (
        !(
          (is_string($_POST['name']) || $_POST['name'] == null) &&
          (is_string($_POST['description']) || $_POST['description'] == null) &&
          (is_numeric($_POST['qty']) || $_POST['qty'] == null) &&
          (is_numeric($_POST['price']) || $_POST['price'] == null)
        )
      ) {
        $result['message'] = 'Add product failed. Make sure you input data with correct format!';
      } else {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $qty = $_POST['qty'];
        $price = $_POST['price'];
        $image = $_FILES['imgupload'];

        $result = postRequest(
          'http://localhost:8068/web/uas/api/router/product.router.php',
          array(
            "func" => "create",
            "name" => $name,
            "description" => $description,
            "qty" => $qty,
            "price" => $price,
            "img" => curl_file_create($image['tmp_name'], $image['type'], $image['name']),
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
        }
      }

    } else {
      header("location:store.php");
    }
  } else {
    header("location:store.php");
  }
} else {
  $result['message'] = null;
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
  <title>Add Product | Sanapati Food Store</title>
</head>

<body>


  <?php
  generateHeader('store');
  ?>

  <div class="container p-5 my-5">
    <div class="row">
      <?php
      if ($result['message'] == null) {
        echo '<p class="text-success text-center">Add product by fill form below</p>';
      } else {
        if ($result['status'] == true) {
          echo '<p class="text-success text-center">' . $result['message'] . '</p>';
        } else {
          echo '<p class="text-danger text-center">' . $result['message'] . '</p>';
        }
      }
      ?>
      <div class="col-lg-4 col-md-6">
        <form class="" method="post" action="productadd.php" enctype="multipart/form-data">
          <input name="imgupload" id="imgupload" class="form-control form-control" type="file"
            accept="image/jpeg,image/png">
          <label class="text-center mb-3" style="font-size:13px;">Make sure to upload jpeg/png image with 1:1
            ratio</label>
      </div>
      <div class="col-lg-8 col-md-6">
        <input name="name" class="form-control mb-2" placeholder="Name" type="text" required>
        <textarea name="description" class="form-control my-2" placeholder="Description" required></textarea>
        <input name="price" class="form-control my-2" placeholder="Price" type="number" required>
        <input name="qty" class="form-control mt-2 mb-4" placeholder="Qty" type="number" required>
        <button type="submit" name="action" value="create" class="btn btn-primary btn-block">Add product</button>
        <a href="store.php" class="btn btn-dark">Return to Store</a>
        </form>
      </div>
    </div>
  </div>

  <!-- End of Jumbotron -->

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