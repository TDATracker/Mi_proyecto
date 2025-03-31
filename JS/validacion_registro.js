document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll(".img-option");
    const formContainer = document.getElementById("form-container");

    images.forEach(img => {
        img.addEventListener("click", function () {
            // Quitar selección previa
            images.forEach(i => i.classList.remove("selected"));

            // Agregar borde a la imagen seleccionada
            this.classList.add("selected");

            // Obtener el formulario correspondiente
            const selectedForm = this.getAttribute("data-form");
            
            // Cargar el formulario correspondiente usando fetch
            loadForm(selectedForm);
        });
    });

    // Función para cargar formularios externos
    function loadForm(formType) {
        let formFile = "";
        
        switch(formType) {
            case "form1":
                formFile = "formulario_usuario.html";
                break;
            case "form2":
                formFile = "formulario_especialista.html";
                break;
            case "form3":
                formFile = "formulario_centros.html";
                break;
        }

        fetch(formFile)
            .then(response => response.text())
            .then(html => {
                formContainer.innerHTML = html;
                // Configurar validaciones después de cargar el formulario
                setupFormValidation(formType);
            })
            .catch(error => {
                console.error("Error al cargar el formulario:", error);
                formContainer.innerHTML = "<p>Error al cargar el formulario. Por favor intente nuevamente.</p>";
            });
    }

    // Función para configurar validaciones según el formulario
    function setupFormValidation(formType) {
        switch(formType) {
            case "form1":
                setupForm1Validation();
                break;
            case "form2":
                setupForm2Validation();
                break;
            case "form3":
                setupForm3Validation();
                break;
        }
    }

    // Resto de las funciones de validación (setupForm1Validation, setupForm2Validation, setupForm3Validation)
    // ... (mantener exactamente las mismas funciones que ya tienes en tu archivo original)
    // [Aquí irían todas las funciones de validación que ya tenías, sin cambios]

    // Función para validar Formulario 1 (Registro Completo)
    function setupForm1Validation() {
        const form = document.getElementById("form-usuario");
        const nombre = document.getElementById("nombre");
        const apellido = document.getElementById("apellido");
        const email = document.getElementById("email");
        const genero = document.getElementById("genero");
        const telefono = document.getElementById("telefono");

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            let isValid = true;

            // Validar nombre
            const nombreValue = nombre.value.trim();
            if (nombreValue === "") {
                document.getElementById("nombre-error").textContent = "El nombre es obligatorio";
                nombre.classList.add("is-invalid");
                isValid = false;
            } else if (nombreValue.length < 3) {
                document.getElementById("nombre-error").textContent = "El nombre debe tener al menos 3 caracteres";
                nombre.classList.add("is-invalid");
                isValid = false;
            } else {
                nombre.classList.remove("is-invalid");
                document.getElementById("nombre-error").textContent = "";
            }

            // Validar apellido
            const apellidoValue = apellido.value.trim();
            if (apellidoValue === "") {
                document.getElementById("apellido-error").textContent = "El apellido es obligatorio";
                apellido.classList.add("is-invalid");
                isValid = false;
            } else if (apellidoValue.length < 3) {
                document.getElementById("apellido-error").textContent = "El apellido debe tener al menos 3 caracteres";
                apellido.classList.add("is-invalid");
                isValid = false;
            } else {
                apellido.classList.remove("is-invalid");
                document.getElementById("apellido-error").textContent = "";
            }

            // Validar email
            const emailValue = email.value.trim();
            if (emailValue === "") {
                document.getElementById("email-error").textContent = "El correo electrónico es obligatorio";
                email.classList.add("is-invalid");
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                document.getElementById("email-error").textContent = "Ingrese un correo válido";
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("email-error").textContent = "";
            }

            // Validar contraseña
            const passwordValue = password.value.trim();
            if (passwordValue === "") {
                document.getElementById("password-error").textContent = "La contraseña es obligatoria";
                password.classList.add("is-invalid");
                isValid = false;
            } else if (passwordValue.length < 6) {
                document.getElementById("password-error").textContent = "Mínimo 6 caracteres";
                password.classList.add("is-invalid");
                isValid = false;
            } else if (!/[A-Z]/.test(passwordValue)) {
                document.getElementById("password-error").textContent = "Debe contener al menos una mayúscula";
                password.classList.add("is-invalid");
                isValid = false;
            } else if (!/[a-z]/.test(passwordValue)) {
                document.getElementById("password-error").textContent = "Debe contener al menos una minúscula";
                password.classList.add("is-invalid");
                isValid = false;
            } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordValue)) {
                document.getElementById("password-error").textContent = "Debe contener al menos un carácter especial";
                password.classList.add("is-invalid");
                isValid = false;
            } else {
                password.classList.remove("is-invalid");
                document.getElementById("password-error").textContent = "";
            }

            if (isValid) {
                alert("Formulario de registro completado con éxito!");
                form.submit(); // Descomentar para enviar realmente
            }
        });
    }

    // Función para validar Formulario 2 (Registro Básico)
    function setupForm2Validation() {
        const form = document.getElementById("form-basico");
        const nombre = document.getElementById("nombre2");
        const apellido = document.getElementById("apellido2");
        const email = document.getElementById("email2");
        const genero = document.getElementById("genero2");
        const telefono = document.getElementById("telefono2");

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            let isValid = true;

            // Validar nombre
            const nombreValue = nombre.value.trim();
            if (nombreValue === "") {
                document.getElementById("nombre-error").textContent = "El nombre es obligatorio";
                nombre.classList.add("is-invalid");
                isValid = false;
            } else if (nombreValue.length < 3) {
                document.getElementById("nombre-error").textContent = "El nombre debe tener al menos 3 caracteres";
                nombre.classList.add("is-invalid");
                isValid = false;
            } else {
                nombre.classList.remove("is-invalid");
                document.getElementById("nombre-error").textContent = "";
            }

            // Validar apellido
            const apellidoValue = apellido.value.trim();
            if (apellidoValue === "") {
                document.getElementById("apellido-error").textContent = "El apellido es obligatorio";
                apellido.classList.add("is-invalid");
                isValid = false;
            } else if (apellidoValue.length < 3) {
                document.getElementById("apellido-error").textContent = "El apellido debe tener al menos 3 caracteres";
                apellido.classList.add("is-invalid");
                isValid = false;
            } else {
                apellido.classList.remove("is-invalid");
                document.getElementById("apellido-error").textContent = "";
            }

            // Validar email
            const emailValue = email.value.trim();
            if (emailValue === "") {
                document.getElementById("email2-error").textContent = "El correo electrónico es obligatorio";
                email.classList.add("is-invalid");
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                document.getElementById("email2-error").textContent = "Ingrese un correo válido";
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("email2-error").textContent = "";
            }

            // Validar género
            const generoValue = genero.value;
            if (generoValue === "") {
                document.getElementById("genero2-error").textContent = "Seleccione un género";
                genero.classList.add("is-invalid");
                isValid = false;
            } else {
                genero.classList.remove("is-invalid");
                document.getElementById("genero2-error").textContent = "";
            }

            if (isValid) {
                alert("Registro básico completado con éxito!");
                form.submit(); // Descomentar para enviar realmente
            }
        });
    }

    // Función para validar Formulario 3 (Contacto)
    function setupForm3Validation() {
        const form = document.getElementById("form-contacto");
        const nombre = document.getElementById("nombre3");
        const direccion = document.getElementById("direccion");
        const email = document.getElementById("email3");
        const telefono = document.getElementById("telefono3");

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            let isValid = true;

            // Validar nombre
            const nombreValue = nombre.value.trim();
            if (nombreValue === "") {
                document.getElementById("nombre3-error").textContent = "El nombre es obligatorio";
                nombre.classList.add("is-invalid");
                isValid = false;
            } else {
                nombre.classList.remove("is-invalid");
                document.getElementById("nombre3-error").textContent = "";
            }

            // Validar dirección
            const direccionValue = direccion.value.trim();
            if (direccionValue === "") {
                document.getElementById("direccion-error").textContent = "La dirección es obligatoria";
                direccion.classList.add("is-invalid");
                isValid = false;
            } else {
                direccion.classList.remove("is-invalid");
                document.getElementById("direccion-error").textContent = "";
            }

            // Validar email
            const emailValue = email.value.trim();
            if (emailValue === "") {
                document.getElementById("email3-error").textContent = "El correo electrónico es obligatorio";
                email.classList.add("is-invalid");
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                document.getElementById("email3-error").textContent = "Ingrese un correo válido";
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("email3-error").textContent = "";
            }

            // Validar teléfono
            const telefonoValue = telefono.value.trim();
            if (telefonoValue === "") {
                document.getElementById("telefono3-error").textContent = "El teléfono es obligatorio";
                telefono.classList.add("is-invalid");
                isValid = false;
            } else if (!/^\d{10}$/.test(telefonoValue)) {
                document.getElementById("telefono3-error").textContent = "Debe contener exactamente 10 dígitos";
                telefono.classList.add("is-invalid");
                isValid = false;
            } else {
                telefono.classList.remove("is-invalid");
                document.getElementById("telefono3-error").textContent = "";
            }

            if (isValid) {
                alert("Formulario de contacto enviado con éxito!");
                form.submit(); // Descomentar para enviar realmente
            }
        });
    }
});