let action = '';
let updateId = '';

function performAction() {
    if (action === "Update") {
        update();
        action = '';
    } else {
        create();
    }
    $('#insertData').modal('hide');
}

function create() {

    const name = document.getElementById('name').value
    const price = document.getElementById('price').value
    const stock = document.getElementById('stock').value

    if (name == "" || price == 0 || stock == 0) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    const formData = new FormData();
    formData.append("name", name);
    formData.append("price", price);
    formData.append("stock", stock);

    fetch("../controllers/productC.php", {
        method: "POST",
        body: formData
    })
        .then(response => {
            if (response.ok) {
                return true;
            } else {
                throw new Error('Error en la solicitud');
            }
        }).then(data => {
            if (data) {
                Swal.fire({
                    title: 'Producto registrado con exito',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
                clearData();
                read();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Revise los datos y vuelva a intentarlo',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })

        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: 'Error',
                text: 'Error en la solicitud',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
}

function read() {
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
            const tbody = document.getElementById('dataResult');
            tbody.innerHTML = '';
            data.forEach(data => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${data.name}</td>
                    <td>${data.price}</td>
                    <td>${data.stock}</td>
                    <td class="actions">
                        <i class="bi bi-pencil-square option" onclick="findById(${data.id})"></i>
                        <i class="bi bi-trash option" onclick="confirmDeletion(${data.id})"></i>
                    </td>`;

                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });
}

function findById(id) {
    fetch(`../controllers/productC.php?id=${id}`, {
        method: "GET",
    })
        .then(response => {
            if (response.ok) {
                updateId = id;
                action = "Update";
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(data => {
            $('#name').val(data.name);
            $('#price').val(data.price);
            $('#stock').val(data.stock);
        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });

    $('#insertData').modal('show');
}

function update() {

    const name = document.getElementById('name').value
    const price = document.getElementById('price').value
    const stock = document.getElementById('stock').value

    if (name == "" || price == 0 || stock == 0) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    const formData = new FormData();
    formData.append("name", name);
    formData.append("price", price);
    formData.append("stock", stock);
    formData.append("id", updateId);

    fetch(`../controllers/productC.php`, {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Producto modificado con exito',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
                clearData();
                read();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Revise los datos y vuelva a intentarlo',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })

        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: 'Error',
                text: 'Error en la solicitud',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
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
            deletedProduct(id);
        }
    });
}

function deletedProduct(userId) {

    let data = { id: userId };

    fetch("../controllers/productC.php", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.ok) {
                return true;
            } else {
                throw new Error('Error en la solicitud');
            }
        })
        .then(data => {
            if (data) {
                Swal.fire({
                    title: 'Producto eliminado con éxito',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'El producto no pudo ser eliminado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
            read();
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: 'Error',
                text: 'Error en la solicitud',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
}

function clearData() {
    $('#name').val('');
    $('#price').val('');
    $('#stock').val('');
}