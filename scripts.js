function get() {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () {
            document.getElementsByTagName('tbody')[0].innerHTML = xhr.responseText;
    };

    xhr.open('GET', '/?action=get');
    xhr.send();
}

function remove(id) {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () { get(); };

    xhr.open('DELETE', '/?action=remove&id=' + id);
    xhr.send();
}

function changeStatus(id, newStatus) {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () { get(); };

    xhr.open('PATCH', '/?action=update&id=' + id + '&status=' + newStatus);
    xhr.send();
}

function events() {
    document.getElementsByTagName('form')[0].addEventListener('submit', function(e){
        e.preventDefault();

        var xhr = new XMLHttpRequest();

        xhr.onload = function () { get(); };

        xhr.open('POST', '/?action=add');
        xhr.send(new FormData(this));
    });
}
