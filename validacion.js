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