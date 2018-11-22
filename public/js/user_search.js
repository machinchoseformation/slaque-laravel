function userSearch(e){
    e.preventDefault();
    var url = $("#user-search-form").attr("action");
    $.ajax({
        url: url,
        data: $("#user-search-form").serialize()
    })
    .done(function(response){
        $("#user-search-result").empty();

        if (response.data.length < 1){
            $("#user-search-result").append('<li>Pas de résultats</li>');
        }

        for(var i = 0; i < response.data.length; i++){
            var user = response.data[i];
            var li = `
                <li><a href="${user.invite_url.replace('%groupId%', groupId)}">${user.name}<a></li>
            `;
            $("#user-search-result").append(li);
        }
    })
}

$("#user-search-form").on("submit", userSearch);
$("#user-search-input").on("keyup", userSearch);