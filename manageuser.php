<?php
session_start();

include 'util/request.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'changerole') {
        $result = postRequest(
            'http://localhost:8068/web/uas/api/router/user.router.php',
            array(
                "func" => "switchrole",
                "id" => $_GET['id'],
            )
        );
        echo '
        <script>
            alert("' . $result['message'] . '");
        </script>
        ';
    } else if ($_GET['action'] == 'delete') {
        $result = getRequest(
            'http://localhost:8068/web/uas/api/router/user.router.php',
            array(
                "func" => "delete",
                "id" => $_GET['id'],
            )
        );
        echo '
        <script>
            alert("' . $result['message'] . '");
        </script>
        ';
    } else {
        header("manageuser.php");
    }
}
$result = getRequest(
    'http://localhost:8068/web/uas/api/router/user.router.php',
    array(
        "func" => "getAll",
    )
);

$users = $result['data'];
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
    <title>Manage User | Sanapati Food Store</title>
</head>

<!-- <body class="bg-primary"> -->

<body>
    <div class="d-flex justify-content-center">
        <div class="col-lg-8 p-5 justify-content-center">
            <!-- <div class="card"> -->

            <article class="card-body bg-dark">
                <h1 class="card-title text-center mt-4 text-light">Manage user</h1>
                <h6 class="card-title text-center mb-5 text-light">Sanapati Food Store</h6>
                <hr style="border-top: 1px solid white;">
                <p class="text-primary text-center">Manage user below</p>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover table-dark">
                        <thead>
                            <tr>
                                <th scope="col" class="th-lg">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <!-- <th scope="col">Password</th> -->
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $idx = 1;
                            foreach ($users as $user) {
                                echo '
                                        <tr>
                                        <th scope="row">' . $idx . '</th>
                                        <td>' . $user['name'] . '</td>
                                        <td>' . $user['username'] . '</td>
                                        
                                        <td>' . $user['role'] . '</td>
                                        <td>';
                                if ($user['id'] != $_SESSION['id']) {
                                    if ($user['role'] != 'admin') {
                                        echo '<button type="button" onclick="confirmForm(' . $user['id'] . ',`' . $user['name'] . '`, `changerole`)" class="btn btn-outline-success btn-sm" style="width:75px;">Promote</button>';
                                        echo " ";
                                    } else {
                                        echo '<button type="button" onclick="confirmForm(' . $user['id'] . ',`' . $user['name'] . '`, `changerole`)" class="btn btn-outline-warning btn-sm" style="width:75px;">Demote</button>';
                                        echo " ";
                                    }
                                    ;
                                    echo '<button type="button" onclick="confirmForm(' . $user['id'] . ',`' . $user['name'] . '`, `delete`)" class="btn btn-outline-danger btn-sm" style="width:75px;">Delete</button>';
                                } else {
                                    echo '<a href="editprofile.php?"><button type="button" class="btn btn-outline-primary btn-sm" style="width:75px;">Edit user</button></a>';
                                }
                                ;
                                echo '</td>
                                            </tr>
                                            ';
                                $idx = $idx + 1;
                            }
                            ?>
                        </tbody>
                    </table>
                    <script>
                        function confirmForm(id, name, operation) {
                            var confirm = window.confirm("Confirm to " + (operation == 'changerole' ? 'change role' : 'delete') + " " + name + "?")
                            if (confirm == true) {
                                if (operation == 'changerole') {
                                    var confirmchangerole = prompt("Type in CHANGEROLE to continue")
                                    if (confirmchangerole == 'CHANGEROLE') {
                                        window.location.href = "manageuser.php?action=changerole&id=" + id
                                    } else {
                                        alert("Change role cancelled")
                                        return false
                                    }
                                } else if (operation == 'delete') {
                                    var confirmchangerole = prompt("Type in DELETE to continue")
                                    if (confirmchangerole == 'DELETE') {
                                        window.location.href = "manageuser.php?action=delete&id=" + id
                                    } else {
                                        alert("Delete user cancelled")
                                        return false
                                    }
                                } else {
                                    alert("Operation cancelled")
                                    return false
                                }
                            } else {
                                alert("Operation cancelled")
                                return false
                            }
                        }
                    </script>
                </div>

                <hr style="border-top: 1px solid white;">
                <p class="text-center"><a href="index.php" class="btn">Go back to Home</a></p>
            </article>


            <!-- </div> -->
        </div>
    </div>

</body>

</html>