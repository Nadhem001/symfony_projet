function supprimer_reservation_ajax(url,id)
{
    $.ajax({
        method:'POST',
        url:url,
        data: {id:id}
    }).done(function(data){
       $("#reservation_"+id).remove();
    })
}
function reservation_to_emprunt(url,id)
{
    $.ajax({
        method:'POST',
        url:url,
        data: {id:id}
    }).done(function(data){
       $("#reservation_"+id).remove();
    })
}


$(document).ready(function() {

    $(document).on("click",".btn_supprimer_reservation",function(){
        url = $(this).data('path');
        id =  $(this).data('id');
        supprimer_reservation_ajax(url,id);
    })

    $(document).on("click",".reservation_to_emprunt",function(){
        url = $(this).data('path');
        id =  $(this).data('id');
        reservation_to_emprunt(url,id);
    })


        //Reservation livre
        $(document).on("change",".modifier_reservation #liste_livre, .modifier_reservation #reservation_date_fin,.modifier_reservation #reservation_date_debut",function(){
            
          
            id_livre = $("#liste_livre").val();

            date_debut = $("#reservation_date_debut").val()
            date_fin = $("#reservation_date_fin").val()
            reservation_livre_ajax(id_livre,date_debut,date_fin)      //livre.js      
    
          
        })

})