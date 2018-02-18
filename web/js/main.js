createNotification = function (message, type, position,element) {
    type = typeof type !== 'undefined' ? type : 'success';

    type = type === 'error' ? 'danger' : type;

if (element !== undefined){
    return  $(element).notify(message,{ position:position,className:type });
}else{
    return $.notify(message,{ position:position,className:type })};
};

