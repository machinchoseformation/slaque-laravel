var messageForm = $("#message-form");
var lastMessageDate = null;
var shownMessageIds = [];

//transmet le jeton csrf à toutes les requêtes
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//supprime un message
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
    });
}

//récupère les nouveaux messages
function getMessageSince(){
    $.ajax({
        url: getMessageUrl,
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

//ajoute le message au chat
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

    var content = messageData.content;
    if (messageData.is_link){
        content = `<a href="${content}">${content}</a>`;
    }

    var str = `
        <article id="article-message-${messageData.id}" class="message ${messageClass}">
            <div class="row">
                <div class="message-content">
                    <div class="message-by">${messageData.creator_name} à ${messageData.time}</div>
                    <p>${content}</p>
                </div>
                ${messageTools}
            </div>
        </article>
    `;
    $("#messages-list").append(str);
    if (messageData.is_link && messageData.link_info) {
        addLinkPreview(messageData, messageData.id)
    }
    shownMessageIds.push(messageData.id);

    scrollDown();

}

//envoie le message au serveur
function sendMessage(e) {
    e.preventDefault();
    var message = $("#message-input").val();
    $.ajax({
        url: messageForm.attr("action"),
        data: messageForm.serialize(),
        method: "post"
    }).
    done(function(response){
        $("#message-input").val("");
        getMessageSince();
        if (response.data.is_link) {
            getLinkPreview(response.data.id, response.data.content);
        }
    });
}

//scroll au bas du chat
function scrollDown(){
    var messageList    = $("#messages-list");
    var height = messageList[0].scrollHeight;
    messageList.scrollTop(height);
}

//récupère le preview pour les liens
function getLinkPreview(messageId, link)
{
    $.ajax({
        url: linkPreviewUrl,
        data: {
            messageId: messageId,
            link: link
        }
    })
    .done(function(response){
        addLinkPreview(response.data, messageId)
    })
}

//ajoute le preview au chat
function addLinkPreview(data, messageId){
    var favi = "";
    var linkInfo = data.link_info;

    if (data.is_link_to_image){
        var preview = `
        <div class="link-preview image">
            <img src="${assetUrl}img/groups/${linkInfo.local_name}">
        </div>
        `;
    }
    else {
        if (linkInfo.favicon){
            favi = `<img class="favi" src="${linkInfo.favicon}">`;
        }
        var preview = `
        <div class="link-preview">
            <h4>${favi} ${linkInfo.title}</h4>
            <p>${linkInfo.description}</p>
        </div>
        `;
    }

    $(`article#article-message-${messageId}`).append(preview);
    scrollDown();
}

//event listeners
messageForm.on("submit", sendMessage);
$("#messages-list").on("click", ".delete-btn", deleteMessage);

//récupère les nouveaux messages toutes les x ms
window.setInterval(getMessageSince, 6000);

//récupère les messages au chargement
getMessageSince();

//supprime les messages flash après 3 secondes
$(".flash-message").delay(3000).slideUp();