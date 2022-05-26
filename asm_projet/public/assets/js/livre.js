function reservation_livre_ajax(id_livre,date_debut,date_fin){
    $.ajax({
        method:'POST',
        url:$("#action_livre").val(),
        data: {
            id_livre:id_livre,
            date_debut:date_debut,
            date_retour:date_fin,
        }
    }).done(function(data){
       if(data == "200"){
            $("#modal_alert_action_livre_mssg").text("Votre demande est acceptée.")
            $("#modif_reservation").prop("disabled",false);
            $(".alert_date_indispo").hide();
           
       }else{
            $("#modal_alert_action_livre_mssg").text("Ce livre est indisponible dans cette date")
            $("#modif_reservation").prop("disabled",true);
            $(".alert_date_indispo").show();

       }
        
       $('#modal_alert_action_livre').modal('show')
    })
}



function supprimer_livre_ajax(url,id)
{
    $.ajax({
        method:'POST',
        url:url,
        data: {id:id}
    }).done(function(data){
       $("#livre_"+id).remove();
    })
}

function add_auteur_ajax(){

    nom_auteur = $("#nom_auteur").val();
    description_auteur = $("#description_auteur").val();
   
    $.ajax({
    method:'POST',
    url:$("#add_auteur_ajax_path").val(),
    data: {
        nom_auteur:nom_auteur,
        description_auteur:description_auteur
         }
    }).done(function(data){
        $('#livres_auteurs').append(new Option(nom_auteur, data))
         $("#livres_auteurs").val(data).change();
         $('#ajout_auteur_modal').modal('hide');
    })

}

function get_livre_commun(){
    $.ajax({
        method:'POST',
        url:$("#livre_commun").val(),
        data: {
            base_sur:type,
            value:value
             }
    }).done(function(data){
            $('#livres_auteurs').append(new Option(nom_auteur, data))
             $("#livres_auteurs").val(data).change();
             $('#ajout_auteur_modal').modal('hide');
    }) 
}
$(document).ready(function() {
    
    $(document).on("click",".btn_supprimer",function(){
        url = $(this).data('path');
        id =  $(this).data('id');
        supprimer_livre_ajax(url,id);
    })

    $("#ajout_auteur_btn").click(function(){
        
        $('#ajout_auteur_modal').modal('show')

    })

    $("#btn_ajout_auteur_modeal").click(function(){

        add_auteur_ajax()

    })
    $(document).on("change","#reservation_date_debut",function(){
        $("#reservation_date_fin").datepicker('option','minDate', $("#reservation_date_debut").val())
    })

    
    $(document).on("change","#emprunt_livre",function(){
      
        $("#emprunt_date_sortie,#emprunt_date_retour").prop( "disabled", false );

        $("#reservation_date_debut,#reservation_date_fin").prop( "disabled", true );
        
        $("#applique_action_livre").prop( "disabled", false );  

      })
      $(document).on("change","#reserver_livre",function(){
      
        $("#emprunt_date_sortie,#emprunt_date_retour").prop( "disabled", true );
      
        $("#reservation_date_debut,#reservation_date_fin").prop( "disabled", false );
      
        $("#applique_action_livre").prop( "disabled", false );
          
      })




    //Reservation livre
     $(document).on("click","#applique_action_livre",function(){
        IsUser = $("#isUser").val()
      
        id_livre= $('input[name="action_user_livre"]:checked').data('id')
        if(IsUser == "true"){
                        
            date_debut = $("#reservation_date_debut").val()
            date_fin = $("#reservation_date_fin").val()
            reservation_livre_ajax(id_livre,date_debut,date_fin)            

        }else{
        
            $('#modal_alert_connexion').modal('show')

        }
    })

});