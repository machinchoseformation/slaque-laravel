var messageForm = $("#message-form");
var refreshBtn = $("#refresh-btn");
var lastMessageDate = null;
var getMessageInterval;
var shownMessageIds = [];

function deleteMessage(e){
    e.preventDefault();
    var messageElement = $(this).parents('.message');
    var messageId = $(this).data("message-id");
    $.ajax({
        url: deleteUrl,
        data: {
            messageId: messageId
        },
        method: 'GET'
    })
    .done(function(response){
        messageElement.slideUp();
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

    var str = `
        <article class="message">
            <div class="message-by">${messageData.creator_name} à ${messageData.time}</div>
            <p>${messageData.content}</p>
            <button class="delete-btn" data-message-id="${messageData.id}">X</button>
        </article>
    `;
    $("#messages-list").append(str);
    shownMessageIds.push(messageData.id);
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
        getMessageSince();
    });
}

messageForm.on("submit", sendMessage);
refreshBtn.on("click", function(e){
    e.preventDefault();
    getMessageSince();
});

$("#messages-list").on("click", ".delete-btn", deleteMessage);

getMessageSince();
//getMessageInterval = window.setInterval(getMessageSince, 2000);