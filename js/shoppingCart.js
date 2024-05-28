let updateId = '';
let products = [];
let clients = [];
let productsCart = [];
let total = 0;
let currentId = 0;

function readProducts() {

    fetch("../controllers/productC.php", {
        method: "GET",
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(data => {

            const productSelect = document.getElementById('product');
            productSelect.innerHTML = '';

            const option = document.createElement('option');
            option.value = '';
            option.text = 'Seleccione...';
            productSelect.appendChild(option);

            products = data;

            data.forEach(product => {
                if (product.stock > 0) {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.text = `${product.name} - $${product.price}`;
                    productSelect.appendChild(option);
                }
            });

        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });

}

function readClients() {
    fetch("../controllers/userC.php", {
        method: "GET",
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(data => {

            const clientSelect = document.getElementById('client');
            clients = data;

            data.forEach(client => {
                const option = document.createElement('option');
                option.value = client.id;
                option.text = `${client.user}`;
                clientSelect.appendChild(option);
            });

        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });
}

function addCar() {

    const productId = document.getElementById('product').value;
    const quantityCart = document.getElementById('quantity').value;

    if (productId == "" || quantityCart == 0) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    let infoProduct = {};

    fetch(`../controllers/productC.php?id=${productId}`, {
        method: "GET",
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(data => {

            infoProduct = data;
            const product = products.find(p => p.id == productId);

            if (product.stock < quantityCart) {
                Swal.fire({
                    title: 'Error',
                    text: 'No hay suficiente stock',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            product.stock = product.stock - quantityCart;
            currentId++;

            const productAddCart = {
                id: currentId,
                productId: productId,
                name: infoProduct.name,
                quantity: quantityCart,
                price: infoProduct.price,
                total: quantityCart * infoProduct.price
            }

            productsCart.push(productAddCart);

            updateCartTable();

            clearData();

        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });

}

function makePurchase() {
    if (productsCart.length > 0) {
        readClients();

        total = productsCart.reduce((acc, product) => acc + product.total, 0);
        document.getElementById('total').value = total;

        $('#makePurchase').modal('show');
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Por favor agrega al menos un producto al carrito',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
}

function showUpdateProduct(id) {
    updateId = id;
    const product = productsCart.find(p => p.id == updateId);
    $('#stock').val(product.quantity);
    $('#updateProduct').modal('show');
}

function updateProduct() {

    const updateStock = document.getElementById('stock').value;

    if (updateStock > 0) {

        const product = productsCart.find(p => p.id === updateId);
        const originalProduct = products.find(p => p.name === product.name);

        originalProduct.stock += parseInt(product.quantity);
        product.quantity = updateStock;
        product.total = product.quantity * product.price;
        originalProduct.stock -= product.quantity;

        total = productsCart.reduce((acc, product) => acc + product.total, 0);
        document.getElementById('total').value = total;

        Swal.fire({
            title: 'Stock modificado con éxito',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });

        updateCartTable();

        $('#stock').val('');
        $('#updateProduct').modal('hide');
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Por favor ingresa un valor mayor a 0',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
}

function confirmDeletion(id) {
    Swal.fire({
        title: 'Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleted(id);
        }
    });
}

function deleted(id) {
    const index = productsCart.findIndex(p => p.id == id);

    if (index !== -1) {
        productsCart.splice(index, 1);

        Swal.fire({
            title: 'Producto eliminado con éxito del carrito',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });

        total = productsCart.reduce((acc, product) => acc + product.total, 0);
        document.getElementById('total').value = total;

        updateCartTable();
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Producto no encontrado en el carrito',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
}

function clearData() {
    $('#product').val('');
    $('#quantity').val('');
    $('#stock').val('');
}

function updateCartTable() {
    const tbody = document.getElementById('dataResult');
    tbody.innerHTML = '';
    productsCart.forEach(data => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${data.name}</td>
            <td>${data.quantity}</td>
            <td>${data.price}</td>
            <td>${data.total}</td>
            <td class="actions">
                <i class="bi bi-pencil-square option" onclick="showUpdateProduct(${data.id})"></i>
                <i class="bi bi-trash option" onclick="confirmDeletion(${data.id})"></i>
            </td>`;
        tbody.appendChild(row);
    });
}

function saveDb() {

    const clientId = document.getElementById('client').value;
    let cart = {};

    if (clientId == "") {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    const formDataCart = new FormData();
    formDataCart.append("clientId", clientId);
    formDataCart.append("total", total);
    formDataCart.append("cart", true);

    fetch("../controllers/shoppingCartC.php", {
        method: "POST",
        body: formDataCart
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(cart => {

            productsCart.forEach(product => {

                const formDataProduct = new FormData();
                formDataProduct.append("cartId", cart.id);
                formDataProduct.append("productId", product.productId);
                formDataProduct.append("quantity", product.quantity);
                formDataProduct.append("cartProduct", true);

                fetch("../controllers/shoppingCartC.php", {
                    method: "POST",
                    body: formDataProduct
                })
                    .then(response => {
                        if (response.ok) {
                            return true;
                        } else {
                            throw new Error('Error en la solicitud');
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al guardar los productos del carrito',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
            });

            products.forEach(product => {

                const formData = new FormData();
                formData.append("name", product.name);
                formData.append("price", product.price);
                formData.append("stock", product.stock);
                formData.append("id", product.id);

                fetch(`../controllers/productC.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al actualizar el stock del producto',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });

            });

            Swal.fire({
                title: 'Éxito',
                text: 'La compra se ha guardado correctamente',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });

            productsCart = [];
            total = 0;
            updateCartTable();
            document.getElementById('total').value = '';
            $('#makePurchase').modal('hide');
            readProducts();

        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: 'Error',
                text: 'Error al guardar el carrito',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });

}