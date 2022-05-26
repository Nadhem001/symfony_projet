function verif_emprunt_date_livre_ajax(id_livre,date_debut,date_fin){
    $.ajax({
        method:'POST',
        url:$("#verif_emprent_livre_by_date").val(),
        data: {
            id_livre:id_livre,
            date_debut:date_debut,
            date_retour:date_fin,
        }
    }).done(function(data){
       if(data == "200"){
        $('.alert_date_indispo').hide();
          $("#add_emprunt").prop('disabled',false);        
       }else{
        $('.alert_date_indispo').show();
        $("#add_emprunt").prop('disabled',true);
       }

    })
}


function supprimer_emprunt_ajax(url,id)
{
    $.ajax({
        method:'POST',
        url:url,
        data: {id:id}
    }).done(function(data){
       $("#emprunt_"+id).remove();
    })
}


function get_liste_livre_emprunt_by_user()
{
    $.ajax({
        method:'POST',
        url:$("#liste_emprunt_by_user_url").val(),
        data: {id:id}
    }).done(function(data){
      if(data >= 1){
          $('.alert_user_emprunt_max').show();
          $("#add_emprunt").prop('disabled',true);
      }else{
        $('.alert_user_emprunt_max').hide();
        $("#add_emprunt").prop('disabled',false);


      }
    })
}

function send_alert_mail(email)
{
    $.ajax({
        method:'POST',
        url:$("#send_alert_url").val(),
        data: {email:email.data('mail'),
                id:email.data('id')
            }
    }).done(function(){
        email.parent().html('<i class="fa-solid fa-check"></i>');
    })
}
 
function emprunt_recuperer(id)
{
    $.ajax({
        method:'POST',
        url:$("#emprunt_recuperer_url").val(),
        data: {id:id
            }
    }).done(function(){
            $("#emprunt_"+id).remove();
    })

}

$(document).ready(function() {

    $(document).on("click",".btn_supprimer_emprunt",function(){
        url = $(this).data('path');
        id =  $(this).data('id');
        supprimer_emprunt_ajax(url,id);
    })
    $(document).on("click",".send_alert_mail",function(){
        email =  $(this);
        
        send_alert_mail(email);
    })

    $(document).on("click","#emprunt_recuperer",function(){
        id =  $(this).data("id");
        
        emprunt_recuperer(id);
    })

    $(document).on("change","#liste_user",function(){
        id =  $(this).val();
        
        get_liste_livre_emprunt_by_user(id);
    })

    $(document).on("change",".add_emprunt #emprunt_date_fin",function(){
        date_debut = $(".add_emprunt #emprunt_date_sortie").val()
        date_fin = $(".add_emprunt #emprunt_date_retour").val()
        id_livre = $(".add_emprunt #liste_livre").val()
         
        verif_emprunt_date_livre_ajax(id_livre,date_debut,date_fin);
    })
 

    $(document).on("change","#emprunt_date_debut",function(){
        $("#emprunt_date_fin").datepicker('option','minDate', $("#emprunt_date_debut").val())
   
    })
})