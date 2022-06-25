let socket = new WebSocket("ws://127.0.0.1:8777");

socket.onopen = function(e) {
    socket.send('{"message": "new room", "value": "one"}');
    console.log("Соединение установлено!");
};

socket.onmessage = function(event) {
    var json = JSON.parse(event.data);
    var messages = document.getElementById("messages");


    var message = '' +
        '<div class="message users">'+
        '<span>'+json.name+'</span>'+
        '<p>'+json.message+'</p>\n' +
        '</div>';

    messages.insertAdjacentHTML('beforeend', message);
};

socket.onclose = function(event) {
    if (event.wasClean) {
        alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
    } else {
        // например, сервер убил процесс или сеть недоступна
        // обычно в этом случае event.code 1006
        alert('[close] Соединение прервано');
    }
};

socket.onerror = function(error) {
    alert(`[error] ${error.message}`);
};