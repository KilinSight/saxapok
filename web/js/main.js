
console.log('letsgo');

let me = '328438276';
let replyTo = me;
$(document).on('click', '.command-buttons', (e)=> {
    let $target = $(e.currentTarget);

    let command = $target.data('command');
    if(command === 'sendAjax'){
        url = Routing.generate('make_request', {method: $('.ajax-url-input').val()});
        body = JSON.parse($('#send-body-textarea').val());
        $('#send-body-textarea').val('{"":}');
    }

    sendAjax(url, body);
    $('#send-message-textarea').val('');
});

function sendAjax(url, body) {
    $.ajax({
        url: url,
        data: body,
        success: (data) => {
            console.log("SUCCESS");
            console.log(data);
        },
        onerror: () => {
            console.log('ERROR');
        }
    })
}