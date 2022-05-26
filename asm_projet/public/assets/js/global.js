





$(document).ready(function() {


    // select2
    $('#livres_auteurs,#liste_user,#liste_livre').select2();

    //datatable
    $('.table').DataTable(
        {
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.12.0/i18n/fr-FR.json"
            }, 
        }
    );
    


    jQuery(function($){
        $.datepicker.regional['fr'] = {
            closeText: 'Fermer',
            prevText: '&#x3c;Préc',
            nextText: 'Suiv&#x3e;',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier','Fevrier','Mars','Avril','Mai','Juin',
            'Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
            monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun',
            'Jul','Aou','Sep','Oct','Nov','Dec'],
            dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
            dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
            dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
            weekHeader: 'Sm',
            dateFormat: 'dd-mm-yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: '',
            minDate: 0,
            maxDate: '+12M +0D',
            numberOfMonths: 2,
            showButtonPanel: true
            };
        $.datepicker.setDefaults($.datepicker.regional['fr']);
    });

    $( ".datepicker" ).datepicker();
});