$( '#uvjet' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: url + 'vozilo/trazi?term=' + req.term + 
                '&rezervacija=' + rezervacija,
           success:function(odgovor){
               res(JSON.parse(odgovor));
        }
       }); 
    },
    minLength: 2,
    select:function(dogadaj,ui){
        console.log(ui.item);
        spremi(ui.item);
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div>' + item.proizvodac + ' ' + item.model + '<div>')
      .appendTo( ul );
  };

function spremi(vozilo){
$.ajax({
    url: url + 'rezervacija/dodajvozilo?rezervacija=' + rezervacija + 
         '&vozilo=' + vozilo.sifra,
    success:function(odgovor){
       $('#podaci').append(
        '<tr>' + 
            '<td>' +
                vozilo.proizvodac + ' ' + vozilo.model +
            '</td>' + 
            '<td>' +
                '<a class="brisiVozilo" href="#" id="p_' + vozilo.sifra +  '">' +
                ' <i style="color: red;" ' +
                ' class="step fi-page-delete size-36"></i>' +
                '</a>' +
            '</td>' + 
        '</tr>'
       );
       definirajBrisanje();
 }
}); 
}

function definirajBrisanje(){
$('.brisiVozilo').click(function(){
    let a =  $(this);
    let vozilo = a.attr('id').split('_')[1];
    $.ajax({
        url: url + 'rezervacija/obrisivozilo?rezervacija=' + rezervacija + 
            '&vozilo=' + vozilo,
        success:function(odgovor){
        a.parent().parent().remove();
    }
    }); 

    return false;
});
}

definirajBrisanje();