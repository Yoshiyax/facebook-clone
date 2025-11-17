function validateRegistrationForm() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.toLowerCase();
    const emailError = document.getElementById('email-error');
    
    const personalDomains = ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.com'];
    const allowedDomains = ['uvg.edu.gt', 'url.edu.gt', 'ufm.edu.gt', 'usac.edu.gt'];
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

    if (!emailRegex.test(email)) {
        emailError.textContent = 'Formato de correo inválido.';
        return false;
    }

    const domain = email.substring(email.lastIndexOf('@') + 1);

    if (personalDomains.includes(domain)) {
        emailError.textContent = 'No se aceptan correos personales (Gmail, Outlook, etc.)';
        return false;
    }
    
    if (!allowedDomains.includes(domain)) {
        emailError.textContent = 'El dominio no pertenece a una universidad permitida.';
        return false;
    }

    emailError.textContent = '';
    return true;
}

//FUNCIÓN PARA FIREBASE
function handleRegistration(event) {
    // Detener el envío del formulario inmediatamente
    event.preventDefault(); 

    // A. Ejecutar la validación de correo existente
    if (!validateRegistrationForm()) {
        return false;
    }

    const fileInput = document.getElementById('avatarFile');
    const file = fileInput.files[0];
    const form = event.target;
    const button = document.getElementById('registerButton');
    
    if (!file) {
        alert("Por favor, selecciona una imagen de avatar.");
        return false;
    }

    // B. Preparar la Interfaz de Usuario
    button.disabled = true;
    button.textContent = 'Subiendo avatar...';

    // C. Subir a Firebase Storage
    const uniqueFileName = Date.now() + '-' + file.name;
    // Usamos storageRef definida en firebase_config.js
    const avatarRef = storageRef.child('avatars/' + uniqueFileName);

    const uploadTask = avatarRef.put(file);

    uploadTask.on('state_changed', 
        (snapshot) => {
            // Progreso de subida (opcional)
        }, 
        (error) => {
            // Manejo de errores
            console.error("Error al subir:", error);
            alert("Error al subir el avatar: " + error.message);
            button.disabled = false;
            button.textContent = 'Registrarme';
        }, 
        () => {
            // Subida completada: Obtener la URL
            uploadTask.snapshot.ref.getDownloadURL().then((downloadURL) => {
                
                // D. Asignar la URL al campo oculto
                document.getElementById('avatarUrlInput').value = downloadURL;
                
                // E. Re-enviar el formulario a PHP para guardar los datos
                form.submit(); 
            });
        }
    );
    
    return false; // Evita el envío inicial
}