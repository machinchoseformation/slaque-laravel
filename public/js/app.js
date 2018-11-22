var messageForm = $("#message-form");
var refreshBtn = $("#refresh-btn");
var lastMessageDate = null;
var shownMessageIds = [];

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function deleteMessage(e){
    e.preventDefault();
    var messageElement = $(this).parents('.message');
    var messageId = $(this).data("message-id");
    $.ajax({
        url: deleteUrl,
        data: {
            messageId: messageId
        },
        method: 'DELETE'
    })
    .done(function(response){
        messageElement.addClass('deleted');
        messageElement.find('.message-content p').html('message supprimé');
        messageElement.find('.delete-btn').hide();
        console.log(response);
    });
}

function getMessageSince(){
    $.ajax({
        url: refreshBtn.attr("href"),
        data: {
            'since': lastMessageDate
        }
    })
    .done(function(response){
        response.data.forEach(function(message){
            if (lastMessageDate == null || message.created_at > lastMessageDate){
                lastMessageDate = message.created_at
            }
            addMessage(message);
        });

    });
}

function addMessage(messageData){
    //already shown !!
    if (shownMessageIds.indexOf(messageData.id) >= 0){
        return false;
    }

    var messageClass = (messageData.deleted) ? 'deleted' : '';

    var messageTools = `<div class="message-tools">`;
    if (!messageData.deleted){
        messageTools += `<button class="delete-btn" data-message-id="${messageData.id}">X</button>`;
    }
    messageTools += `</div>`;

    var str = `
        <article class="message ${messageClass}">
            <div class="message-content">
                <div class="message-by">${messageData.creator_name} à ${messageData.time}</div>
                <p>${messageData.content}</p>
            </div>
            ${messageTools}
        </article>
    `;
    $("#messages-list").append(str);
    shownMessageIds.push(messageData.id);

    var wtf    = $("#messages-list");
    var height = wtf[0].scrollHeight;
    wtf.scrollTop(height);

}

function sendMessage(e) {
    e.preventDefault();
    var message = $("#message-input").val();
    $.ajax({
        url: messageForm.attr("action"),
        data: messageForm.serialize(),
        method: "post"
    }).
    done(function(response){
        console.log(response);
        $("#message-input").val("");
        getMessageSince();
    });
}

function loadUserConversation(e){
    var userId = $(this).data("user-id");
    $.ajax({
        //url:
    })
    .done(function(response){

    })
}

refreshBtn.on("click", function(e){
    e.preventDefault();
    getMessageSince();
});
messageForm.on("submit", sendMessage);
$("#messages-list").on("click", ".delete-btn", deleteMessage);
$(".users-list .user-btn").on("click", loadUserConversation);

window.setInterval(getMessageSince, 2000);

getMessageSince();