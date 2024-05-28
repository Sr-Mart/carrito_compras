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
    <script src='../js/shoppingCart.js'></script>

    <title>Carrito de compras</title>
</head>

<body>
    <div class="modal fade" id="makePurchase" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Realizar compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bodyData" class="bodyData">
                        <div class="form-group">
                            <div>
                                <label for="client">Cliente</label>
                                <select id="client" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div>
                                <label for="total">Total</label>
                                <input type="number" id="total" class="form-control" placeholder="Total" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="clearData()">Cerrar</button>
                    <button type="button" id="btnAdd" name="btnAdd" class="btn btn-outline-secondary" onclick="saveDb()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualizar stock del producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bodyData" class="bodyData">
                        <div class="form-group">
                            <div>
                                <label for="stock">Stock</label>
                                <input type="number" id="stock" class="form-control" placeholder="Stock">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="clearData()">Cerrar</button>
                    <button type="button" id="btnAdd" name="btnAdd" class="btn btn-outline-secondary" onclick="updateProduct()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-5">
        <div class="container shoppingCart">
            <div>
                <label for="name">Producto</label>
                <select id="product" class="form-control">
                    <option value="">Seleccione...</option>
                </select>
            </div>
            <div>
                <label for="quantity">Cantidad</label>
                <input type="number" id="quantity" class="form-control" placeholder="Cantidad">
            </div>
            <div class="addCar">
                <button type="button" id="btnAdd" name="btnAdd" class="btn btn-outline-secondary" onclick="addCar()">Añadir al carro</button>
            </div>
        </div>
    </div>

    <div id="resultData" class="resultData">
        <table class="table table-striped table-dark" id="dataTable">
            <thead class="thead-dark">
                <tr class="table-secondary">
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio unitario</th>
                    <th scope="col">Precio total</th>
                    <th scope="col" colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody id="dataResult"></tbody>
        </table>
    </div>

    <div class="form-group">
        <div class="container shoppingCart">
            <div class="addCar">
                <button type="button" id="btnAdd" name="btnAdd" class="btn btn-outline-secondary" onclick="makePurchase()">Realizar compra</button>
            </div>
        </div>
    </div>
</body>

<script>
    readProducts();
</script>

</html>