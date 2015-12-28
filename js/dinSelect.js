$(document).ready(function() { 
$( "#comuneList" ).autocomplete({
    source: "inc/autoComune.php",
    minLength: 2,
    select: function( event, ui ) {
      event.preventDefault();
      var c = $(this).attr("data-campo");
      //console.log(ui.item.id+'\n'+ui.item.value+'\n'+c); return false;
      $(this).val(ui.item.value);
      $("#comune").val(ui.item.gid);

      $.ajax({
       type: "POST",
       url: "inc/dinSelLocalita.php",
       data: {id:ui.item.gid},
       cache: false,
       success: function(html){$("#localita").removeAttr('disabled').html(html);} 
      });//ajax      
    },
    open: function(){$('.ui-menu .ui-menu-item a').removeClass('ui-corner-all');}
  });
  $("#localita").change(function() {
    var id = $(this).val();
    $.ajax({
     type: "POST",
     url: "inc/dinSelToponimo.php",
     data: {id:id},
     cache: false,
     success: function(html){$("#toponimo").removeAttr('disabled').html(html);} 
    });//ajax
   });
  $("#toponimo").change(function() {
    var id = $(this).val();
    $.ajax({
     type: "POST",
     url: "inc/dinSelMicrotoponimo.php",
     data: {id:id},
     cache: false,
     success: function(html){$("#microtoponimo").removeAttr('disabled').html(html);} 
    });//ajax
   });
  $("#def_gen").change(function() {
    var id = $(this).val();
    var selected = $(this).find('option:selected');
    var ico = selected.data('ico');
    $('#ico').val(ico); 
    $.ajax({
     type: "POST",
     url: "inc/dinSelDefinizioni.php",
     data: {id:id},
     cache: false,
     success: function(html){$("#def_spec").removeAttr('disabled').html(html);} 
    });//ajax
   });
  $("#materiale").change(function() {
    var id = $(this).val();
    $.ajax({
     type: "POST",
     url: "inc/dinSelTecnica.php",
     data: {id:id},
     cache: false,
     success: function(html){$("#tecnica").removeAttr('disabled').html(html);} 
    });//ajax
   });
   
   $( "#cercapoilist" ).autocomplete({
    source: "inc/cercapoi.php",
    minLength: 2,
    select: function( event, ui ) {
      event.preventDefault();
      $(this).val(ui.item.value);
      var newll= new OpenLayers.LonLat(ui.item.lon,ui.item.lat);
      map.setCenter(newll,18);   
    },
    open: function(){$('.ui-menu .ui-menu-item a').removeClass('ui-corner-all');}
  });
});
