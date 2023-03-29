const togglePassword = () => {
    const password = document.querySelector('#password');
    password.type = password.type == 'text' ? 'password' : 'text';

    const eye = document.querySelector('#eye');
    const eyeSlash = document.querySelector('#eye-slash');
    if (password.type == 'password') {
        eye.style.display = '';
        eyeSlash.style.display = 'none';
    } else {
        eyeSlash.style.display = '';
        eye.style.display = 'none';
    }
}