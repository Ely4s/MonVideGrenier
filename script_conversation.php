<script>
getMessage();
setInterval(getMessage, 1000);

$('#messageNew-inpt').focus();

$(document.documentElement).addClass("focus");

$(window).on("blur focus", function(e)
{
    let prevType = $(this).data("prevType");

    if (prevType !== e.type)
    {   //  reduce double fire issues
        switch (e.type) {
            case "blur":
                $(document.documentElement).addClass('blur');
                $('html').removeClass('focus');
                break;
            case "focus":
                $(document.documentElement).addClass('focus');
                $(document.documentElement).removeClass('blur');
                break;
        }
    }
    $(this).data("prevType", e.type);
});


function getMessage()
{
    if($(document.documentElement).hasClass('focus') || $('#messageNew-inpt').hasFocus)
    {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const id_buyer = urlParams.get('id_buyer');
        const id_seller = urlParams.get('id_seller');
        const id_ad = urlParams.get('id_ad');

        $.ajax(
        {
            url: "data.php",
            type: "get",
            data:
            {
                id_buyer: id_buyer,
                id_seller: id_seller,
                id_ad: id_ad
            },
            success: function(response)
            {
                let parser = new DOMParser();
                let json = JSON.parse(response);

                let messages = document.getElementsByClassName('Cnvrstn-Otpt')[0];
                let messagesNbrOld = messages.getAttribute("data-mssg-nbr");
                let scroll_late = messages.scrollTop - (messages.scrollHeight-messages.clientHeight);
                let messagesNbr = json["CONVERSATIONS_messageNbr"];
                messages.setAttribute("data-mssg-nbr", messagesNbr);

                messages.innerHTML = "";

                for(let i = 1; i <= messagesNbr; i++)
                {
                    let id_user = parseInt('<?php echo $_SESSION['id']?>');
                    let side = 'R';
                    if(id_user !== json['CONVERSATIONS_message_'+i]['MESSAGES_id_user'])
                    {side = 'L';}

                    let nw_elmnt =

                    '<div class="Cnvrstn-Mssg Mssg-'+side+'">'+
                        '<div class="Cnvrstn-Dt">'+json['CONVERSATIONS_message_'+i]['MESSAGES_postDatetime']+'</div>'+
                        '<div class="Cnvrstn-MssgTxt">'+nl2br(json['CONVERSATIONS_message_'+i]['MESSAGES_content'], false, false)+'</div>'+
                    '</div>';

                    let message = parser.parseFromString(nw_elmnt, 'text/html');
                    messages.append(message.body.firstChild);

                    if(scroll_late > -75 && messagesNbrOld < messagesNbr)
                    {
                        messages = document.getElementsByClassName('Cnvrstn-Otpt')[0];
                        messages.scrollTop = messages.scrollHeight;
                    }
                }
            },
            error: function(xhr)
            {

            }
        });
    }
}
</script>