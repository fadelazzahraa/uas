<?php
session_start();

include 'util/cart.php';
include 'util/request.php';
include 'template/header.php';
include 'template/footer.php';

$result = getRequest(
  'http://localhost:8068/web/uas/api/router/product.router.php',
  array(
    "func" => "getAll",
  )
);

$products = $result['data'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="dist/bootstrap.min.css" rel="stylesheet">

  <link rel="icon" href="image/favicon.ico" type="image/x-icon">
  <title>Store | Sanapati Food Store</title>
  <style>
    .crop-text-2 {
      -webkit-line-clamp: 2;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-box-orient: vertical;
    }
  </style>
</head>

<body>

  <?php
  generateHeader('store');
  ?>

  <div class="container p-5 my-5">
    <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1 gy-5">


      <?php
      if ($products != []) {
        foreach ($products as $product) {
          echo '<div class="col">';
          echo '<div class="card" style="width: 18rem;">';
          echo '<div class="ratio ratio-1x1">';
          echo '<img src="image/' . $product['imagepath'] . '" class="card-img-top" alt="' . $product['name'] . '">';
          echo '</div>';
          echo '<div class="card-body">';
          echo '<h4 class="card-title">' . $product['name'] . '</h4>';
          echo '<p class="card-text text-truncate">' . $product['description'] . '</p>';
          echo '<h5 class="card-text text-dark">Rp' . $product['price'] . ',00</h5>';
          echo '<p class="card-text text-' . ($product['qty'] > 3 ? "success" : ($product['qty'] == 0 ? "danger" : "warning")) . ' mt-2 mb-3">Qty left: ' . $product['qty'] . '</p>';
          echo '<a href="productdetail.php?id=' . $product['id'] . '" class="btn btn-dark me-2">Detail</a>';
          if (!empty($_SESSION['loggedIn']) && $product['qty'] != 0 && $product['qty'] != $_SESSION['cart'][$product['id']]) {
            echo '<a href="util/addtocartbutton.php?id=' . $product['id'] . '&origin=store.php" class="btn btn-primary me-2">Add to cart</a>';
          }
          if (!empty($_SESSION['loggedIn']) && $_SESSION['role'] == 'admin') {
            echo '<a href="productedit.php?id=' . $product['id'] . '" class="btn btn-success">Edit</a>';
          }
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo '<h1>No product found</h1>';
      }
      ?>

    </div>
  </div>


  <!-- End of Jumbotron -->
  <?php
  if (!empty($_SESSION['loggedIn']) && isCartNotEmpty($_SESSION['cart'])) {

    echo '<div class="position-fixed position-absolute bottom-0 end-0 m-3">';
    echo '<div class="card text-end" style="width: 10rem;">';
    echo '<a href="cart.php" class="btn btn-primary">???? Show cart (' . countCartItem($_SESSION['cart']) . ')</a>';
    echo '</div>';
    echo '</div>';
  }
  ?>
  <br>
  <!-- Footer -->
  <?php
  generateFooter();
  ?>
  <!-- End of Footer -->

  <!-- Script -->
  <script src="dist/bootstrap.bundle.min.js"></script>
  <script src="popper.min.js"></script>
  <!-- End of Script -->

  <link href="dist/bootstrap.min.css" rel="stylesheet">
</body>

</html>