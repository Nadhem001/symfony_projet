function verifEmail(email){
    $.ajax({
        method:'POST',
        url:$("#verif_email").val(),
        data: {
            email: email
        }
    }).done(function(data){
        if(data == "200"){
            
            $("#error_mail").css("display","block");
            $( "#inscription_btn" ).prop( "disabled", true );

        }else{
            $("#error_mail").css("display","none");
            $( "#inscription_btn" ).prop( "disabled", false );

        }
    })
}

function supprimer_user_ajax(url,id)
{
    $.ajax({
        method:'POST',
        url:url,
        data: {id:id}
    }).done(function(data){
       $("#user_"+id).remove();
    })
}



$(document).ready(function() {

    $(document).on("click",".btn_supprimer_user",function(){
        url = $(this).data('path');
        id =  $(this).data('id');
        supprimer_user_ajax(url,id);
    })



    $("#user_email").keyup(function(){
        email = $(this).val();
        verifEmail(email);
    })

    $("#user_num_tele").keyup(function(){
       
        const Regex = /^\d{8}$/;
        const tele = $(this).val();
        
        if(tele.match(Regex)) {

            $("#error_tele").css("display","none");
            $("#inscription_btn" ).prop( "disabled", false );     

        } else {
            $("#error_tele").css("display","block");
            $( "#inscription_btn" ).prop( "disabled", true );
        }
    })

});