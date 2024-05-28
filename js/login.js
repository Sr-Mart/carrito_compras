function Login() {
    
    const user = document.getElementById("user").value;
    const password = document.getElementById("password").value;

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

    fetch("controllers/loginC.php", {
        method: "POST",
        body: formData
    })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Error en la solicitud');
            }
        }).then(data => {
            console.log(data)
            const response = JSON.parse(data);
            if (response.success) {
                Swal.fire({
                    title: 'Bienvenido',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "views/dashboard.php";
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Usuario o contraseña incorrectos',
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