
console.log('letsgo');

let me = '328438276';
let replyTo = me;
$(document).on('click', '.command-buttons', (e)=> {
    let $target = $(e.currentTarget);

    let command = $target.data('command');
    if(command === 'sendAjax'){
        url = Routing.generate('make_request', {method: $('.ajax-url-input').val()});
        body = JSON.parse($('#send-body-textarea').val());
        // $('#send-body-textarea').val('{}');
    }else if(command==='sendMeMessage'){
        $('.ajax-url-input').val('sendMessage');
        $('#send-body-textarea').val(
            '{"chat_id": ' + me + ', "text": "test"}'
        );
    }else if(command==='getWebhookInfo'){
        $('.ajax-url-input').val('getWebhookInfo');
        $('#send-body-textarea').val(
            '{}'
        );
    }else if(command==='testUpdateBody'){
        $('.ajax-url-input').val('testUpdateBody');
        $('#send-body-textarea').val(
            ''
        );
    }

    sendAjax(url, body);
});

function sendAjax(url, body) {
    $.ajax({
        url: url,
        data: {body:body},
        success: (data) => {
            console.log("SUCCESS");
            console.log(data);
        },
        onerror: () => {
            console.log('ERROR');
        }
    })
}