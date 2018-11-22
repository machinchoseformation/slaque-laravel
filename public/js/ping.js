if (undefined === groupId){
    var groupId = null;
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

window.setInterval(ping, 1000);