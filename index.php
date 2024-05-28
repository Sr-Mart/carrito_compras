<?php

session_start();

if (isset($_SESSION['user'])) {
    header('Location: views/dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->

    <link rel="stylesheet" href="assets/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- JS -->
    <script src="assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="js/login.js"></script>

    <title>Carrito de compras</title>
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form>

                        <div class="mb-4">
                            <h2 class="text-center">Login</h2>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="user" class="form-control form-control-lg" placeholder="Ingresa el usuario" />
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="password" id="password" class="form-control form-control-lg" placeholder="Ingresa la contraseña" />
                        </div>

                        <div class="text-center mt-4 pt-2">
                            <button type="button" class="btn btn-primary btn-lg" onclick="Login()">Ingresar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>