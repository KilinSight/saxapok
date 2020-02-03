$(()=> {
    let botapikey = '914200924:AAHcU9V8CCXXHDsealePRW5Yw4ck-Om3Xzg';
    let rootUrl = "https://api.telegram.org/bot" + botapikey + "/";
    let getUpdatesUrl = "https://api.telegram.org/bot" + botapikey + "/getUpdates";
    let body = {};
    let offset = 0;

    let Natasha = '334733456';
    let me = '328438276';
    let bot = '914200924';
    let replyTo = me;

    // let timeout = setInterval(() => {
    //
    //     sendAjax(getUpdatesUrl, {offset});
    // },3000)

    $(document).on('click', '.bot-result', (e)=> {
        let $target = $(e.currentTarget);
        let chatId = $target.find('.message-from').data('chat-id');
        replyTo = chatId;
        $('.bot-result').removeClass('selected');
        $target.addClass('selected');
    });

    $(document).on('click', '.command-buttons', (e)=> {
        let $target = $(e.currentTarget);
        let command = $target.data('command');
        let chatId = $target.data('chat-id');
        let url = "https://api.telegram.org/bot" + botapikey + "/" + command;

        if(command === 'sendMessage'){
            body = {
                text: $('#send-message-textarea').val(),
                chat_id: replyTo
            };
        }else if(command === 'sendPhoto'){
            body = {
                photo: 'https://i.pinimg.com/236x/ea/64/75/ea64756611a42f6a772a91a616efc159.jpg',
                chat_id: chatId
            };
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
                if(data.ok){
                    if(data.result && data.result.length){
                        if(offset < data.result[data.result.length - 1].update_id + 1){
                            offset = data.result[data.result.length - 1].update_id + 1;
                        }
                        $.each(data.result, (index,item) => {
                            console.log(item.message.message_id);
                            if(!$('.bot-result[data-message-id="' + item.message.message_id + '"]').length){
                                $('.bot-results').append(
                                    '<div class="bot-result" data-message-id="' + item.message.message_id + '">' +
                                    '   <div class="message-from" data-chat-id="' + item.message.chat.id + '">' + item.message.chat.username + '</div>' +
                                    '   <div class="message-text">' + item.message.text + '</div>' +
                                    '</div>'
                                );
                            }
                            if(item.message.from.id !== bot){
                                sendAjax(rootUrl + 'sendMessage', {text: 'Ща погоди отвечу', chat_id: item.message.chat.id});
                            }
                        })

                    }

                }
            },
            onerror: () => {
                console.log('ERROR');
            }
        })
    }
});