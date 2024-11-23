function validarYEnviar() {
    var nombre = document.getElementById("nombre").value;
    var dni = document.getElementById("dni").value;
    var fechaNacimiento = document.getElementById("fecha_nacimiento").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;
    var mensaje = "";
    var error = false;

    // Validar DNI
    var x = /\d{8}-[A-Z]{1}/;
    if (!x.test(dni) || dni.length !== 10) {
        mensaje = mensaje.concat("El formato del DNI debe ser 12345678-A. \n");
        error = true;
    } else {
        var num = dni.slice(0, 8);
        var resto = num % 23;
        var letrasDNI = "TRWAGMYFPDXBNJZSQVHLCKE";
        var letraCalculada = letrasDNI.charAt(resto);
        if (letraCalculada !== dni.slice(9, 10)) {
            mensaje = mensaje.concat("El DNI debe ser válido. \n");
            error = true;
        }
    }

    // Verificar mayor de edad
    var fechaNacimientoDate = new Date(fechaNacimiento);
    var hoy = new Date();
    var edad = hoy.getFullYear() - fechaNacimientoDate.getFullYear();
    var mes = hoy.getMonth() - fechaNacimientoDate.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimientoDate.getDate())) {
        edad--;
    }
    if (edad < 18) {
        mensaje = mensaje.concat("Debes ser mayor de edad para registrarte. \n");
        error = true;
    }

    // Verificar coincidencia de contraseñas
    if (password !== confirm_password) {
        mensaje = mensaje.concat("Las contraseñas no coinciden. \n");
        error = true;
    }

    // Mostrar mensajes de error si los hay
    if (error) {
        alert(mensaje);
        return false;
    } else {
        return true;
    }
}

