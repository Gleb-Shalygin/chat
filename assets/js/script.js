var windowExit = document.querySelector('.window__exit');

function addPassword() {
    var checkPassword = document.querySelector('.check__password input');
    var element = document.getElementById('add__password');

    if(checkPassword.checked) {
        element.innerHTML = '<label class="password__input">Пароль</label>\n' +
            '<input type="password" name="password" placeholder="введите пароль">';
    } else {
        element.innerHTML = '';
    }
}

function exitChat() {
    windowExit.classList.add('active');
}

function exitChatNo() {
    windowExit.classList.remove('active');
}