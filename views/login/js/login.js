$(document).ready(function() {
    $('.form-login').submit(function (e) {
        e.preventDefault();
    });
    
    $('#alert').html('').hide();
});

$('#submitLogin').on('click', function() {
    formhash(document.forms[0], document.forms[0].password);
});

function formhash(form, password) { 
    if ($('input[name="username"]').val() && password.value !== '') {
        var user = $('input[name="username"]').val();
        
        $('#alert').html('').hide();

        $.ajax({
            url: "login/run",
            data: {u: user, p: hex_sha512(password.value)},
            type: 'POST',
            dataType: 'json',
            // headers: { 'x-Mown-Token': token },
            success: function (data) {
                window.location.replace(data.redirectURL);
                password.value = "";
            },
            error: function () {
                $('#alert').html('Wrong user or password').show();
            }
        });
    }
}