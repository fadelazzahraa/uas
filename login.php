<?php
include 'util/cart.php';
include 'util/request.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

    $result = postRequest(
        'http://localhost:8068/web/uas/api/router/user.router.php',
        array(
            "func" => "login",
            "username" => $_POST['username'],
            "password" => $_POST['password'],
        ),
    );

    if ($result['status'] == true) {
        $result2 = getRequest(
            'http://localhost:8068/web/uas/api/router/product.router.php',
            array(
                "func" => "getAll",
            )
        );

        if ($result2['status'] == true) {
            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['id'] = $result['data']['id'];
            $_SESSION['name'] = $result['data']['name'];
            $_SESSION['role'] = $result['data']['role'];

            $_SESSION['cart'] = initCart($result2['data']);
            header('location:store.php');
        }
    }

} else if (isset($_GET['login']) && $_GET['login'] == 'false') {
    $result['message'] = 'You need to login before do shopping!';
} else {
    $result = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="icon" href="image/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <title>Login Page | Sanapati Food Store</title>
</head>

<body>


    <div class="d-flex justify-content-center ">
        <div class="col-lg-5 p-5 justify-content-center ">
            <article class="card-body">
                <h1 class="card-title text-center mt-4">Login</h1>
                <h6 class="card-title text-center mb-5">Sanapati Food Store</h6>
                <hr>
                <?php
                if ($result == null) {
                    echo '<p class="text-success text-center">Submit form below</p>';
                } else {
                    echo '<p class="text-danger text-center">' . $result['message'] . '</p>';
                }
                ?>
                <form class="form-signin" method="post" action="login.php">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                            </div>
                            <input class="form-control" placeholder="Username" name="username" type="text" required>
                        </div> <!-- input-group.// -->
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                            </div>
                            <input class="form-control" placeholder="Password" name="password" type="password">
                        </div> <!-- input-group.// -->
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="Login"
                            value="Login">Login</button>
                    </div> <!-- form-group// -->
                    <hr>
                    <p class="text-center"><a href="signup.php" class="btn">Don't have account yet? Sign up</a></p>
                    <p class="text-center"><a href="index.php" class="btn">Go back to home</a></p>
                </form>
            </article>
        </div>
    </div>

</body>

</html>