var messageForm = $("#message-form");
var refreshBtn = $("#refresh-btn");
var lastMessageDate = null;
var getMessageInterval;
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
        messageElement.slideUp('fast');
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

        var wtf    = $("#messages-list");
        var height = wtf[0].scrollHeight;
        wtf.scrollTop(height);
    });
}

function addMessage(messageData){
    //already shown !!
    if (shownMessageIds.indexOf(messageData.id) >= 0){
        return false;
    }

    var str = `
        <article class="message">
            <div class="message-content">
                <div class="message-by">${messageData.creator_name} à ${messageData.time}</div>
                <p>${messageData.content}</p>
            </div>
            <div class="message-tools">
                <button class="delete-btn" data-message-id="${messageData.id}">X</button>
            </div>
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
        $("#message-input").val("");
        getMessageSince();
    });
}

function ping(){
    $.ajax({
        url: pingUrl,
        data: {
            groupId: groupId
        }
    })
        //recoit la liste des utilisateurs connectés en réponse
    .done(function(response){
        $(".users-list li").each(function(index){
            var online = false;
            var el = $(this);

            for(var i = 0; i < response.data.length; i++){
                var user = response.data[i];
                var id = el.data('user-id');
                if (id === user.id){
                    online = true;
                    break;
                }
            }
            el.toggleClass('online', online);
        });
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

window.setInterval(ping, 1000);

messageForm.on("submit", sendMessage);
refreshBtn.on("click", function(e){
    e.preventDefault();
    getMessageSince();
});

$("#messages-list").on("click", ".delete-btn", deleteMessage);

getMessageSince();
//getMessageInterval = window.setInterval(getMessageSince, 2000);


//$(".ma-class").first().click();

$(".users-list .user-btn").on("click", loadUserConversation);