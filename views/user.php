<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>

    <!-- sweetalert -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../assets/node_modules/sweetalert2/dist/sweetalert2.css">

    <!-- iconos-->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="../assets/node_modules/bootstrap-icons/font/bootstrap-icons.css">

    <!-- estilos propios -->
    <link rel="stylesheet" href="../assets/css/forms.css">

    <!-- Funciones js  -->
    <script src='../js/user.js'></script>

    <title>Usuarios</title>
</head>

<body>
    <div class="modal fade" id="insertData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="bodyData" class="bodyData">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <div class="container">
                                <div class="sub-contenedor">

                                    <div class="entrada">
                                        <label for="user">Usuario</label>
                                        <input type="text" id="user" class="form-control" placeholder="Usuario">
                                    </div>

                                    <div class="entrada">
                                        <label for="password">Contraseña</label>
                                        <input type="text" id="password" class="form-control" placeholder="Contraseña">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="clearData()">Cerrar</button>
                    <button type="button" id="btnAdd" name="btnAdd" class="btn btn-outline-secondary" onclick="performAction()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="nuevoRegistro" data-bs-toggle="modal" data-bs-target="#insertData">Nuevo usuario</button>

    <div id="resultData" class="resultData">
        <table class="table table-striped table-dark" id="dataTable">
            <thead class="thead-dark">
                <tr class="table-secondary">
                    <th scope="col">Nombre</th>
                    <th scope="col">Contraseña</th>
                    <th scope="col" colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody id="dataResult"></tbody>
        </table>
    </div>
</body>

<script>
    read();
</script>

</html>