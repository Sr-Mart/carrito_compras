var action = '';
var updateId = '';

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

    user = document.getElementById('user').value
    password = document.getElementById('password').value

    if (user == "" || password == "") {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    const formData = new FormData();
    formData.append("user", user);
    formData.append("password", password);

    fetch("../controllers/userC.php", {
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
                    title: 'Usuario registrado con exito',
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
            const tbody = document.getElementById('dataResult');
            tbody.innerHTML = '';
            data.forEach(user => {
                const row = document.createElement('tr');

                const passwordAsterisks = '*'.repeat(user.password.length);

                row.innerHTML = `
                    <td>${user.user}</td>
                    <td>${passwordAsterisks}</td>
                    <td class="actions">
                        <i class="bi bi-pencil-square option" onclick="findById(${user.id})"></i>
                        <i class="bi bi-trash option" onclick="confirmDeletion(${user.id})"></i>
                    </td>`;

                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });
}

function findById(id) {
    fetch(`../controllers/userC.php?id=${id}`, {
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
            $('#user').val(data.user);
            $('#password').val(data.password);
        })
        .catch(error => {
            console.error("Error encontrado:", error);
        });

    $('#insertData').modal('show');
}

function update() {

    user = document.getElementById('user').value
    password = document.getElementById('password').value

    if (user == "" || password == "") {
        Swal.fire({
            title: 'Error',
            text: 'Por favor llena todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    const formData = new FormData();
    formData.append("user", user);
    formData.append("password", password);
    formData.append("id", updateId);

    fetch(`../controllers/userC.php`, {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Usuario modificado con exito',
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
            deletedUser(id);
        }
    });
}

function deletedUser(userId) {

    var data = { id: userId };

    fetch("../controllers/userC.php", {
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
                    title: 'Usuario eliminado con éxito',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'El usuario no pudo ser eliminado',
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
    $('#user').val('');
    $('#password').val('');
}