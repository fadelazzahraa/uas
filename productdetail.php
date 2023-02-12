<?php
session_start();

include 'util/cart.php';
include 'util/request.php';
include 'template/header.php';
include 'template/footer.php';


if (!empty($_GET)) {
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

  <link href="dist/bootstrap.min.css" rel="stylesheet">

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
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="ratio ratio-1x1">
            <img src="image/<?= $product['imagepath'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-6">
        <h1 class="pt-4">
          <?= $product['name'] ?>
        </h1>
        <p style="text-align:justify;" class="text-align-justify">
          <?= $product['description'] ?>
        </p>
        <h3>Rp
          <?= $product['price'] ?>,00
        </h3>
        <p class="text-<?=($product['qty'] > 3 ? "success" : ($product['qty'] == 0 ? "danger" : "warning")) ?>">Qty
          left:
          <?= $product['qty'] ?></p>
        <?php
        if (!empty($_SESSION['loggedIn']) && $product['qty'] != 0 && $product['qty'] != $_SESSION['cart'][$product['id']]) {
          echo '<a href="util/addtocartbutton.php?id=' . $product['id'] . '&origin=productdetail.php?id=' . $product['id'] . '" class="btn btn-primary me-1">Add to cart</a>';
        }
        if (!empty($_SESSION['loggedIn']) && $_SESSION['role'] == 'admin') {
          echo '<a href="productedit.php?id=' . $product['id'] . '" class="btn btn-success">Edit</a>';
        }
        ?>
        <a href="store.php" class="btn btn-dark">Return to Store</a>
      </div>


    </div>
  </div>
  </div>

  <!-- End of Jumbotron -->

  <?php
  if (!empty($_SESSION['loggedIn']) && isCartNotEmpty($_SESSION['cart'])) {

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
  <script src="dist/bootstrap.bundle.min.js"></script>
  <script src="popper.min.js"></script>
  <!-- End of Script -->
</body>

</html>