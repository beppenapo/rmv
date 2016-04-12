<?php
session_start();
require_once("inc/db.php");

$query=("
select t.id_toponimo, t.id_comune, t.id_localita, t.toponimo, l.localita, c.nome as comune, count(sito.id) as siti
from liste.toponimo t
left join liste.localita l on t.id_localita = l.id_localita
left join comuni c on t.id_comune = c.gid
left join sito on sito.id_toponimo = t.id_toponimo
where t.id_toponimo <> 12
group by t.id_toponimo, t.id_comune, t.id_localita, t.toponimo, l.localita, c.nome
order by comune asc, localita asc, toponimo asc;
");
$result=pg_query($connection, $query);
$r = pg_num_rows($result);
$header = (!$r) ? 'Non sono presenti toponimi nel database ' : 'Lista completa dei toponimi presenti nel database ('.$r.')';
?>
<!DOCTYPE html>
<html>
<head>
<?php require("inc/meta.php"); ?>
<link href="js/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" >
<style>
#newRec{width:100%;margin:10px auto;}
#newRec form{width:50%;margin:0px auto;}
#newRec input, #newRec select{width:100% !important; margin-bottom:10px;padding: 5px; border-radius: 5px; border: 1px solid grey;}
#newRec table{width:90%;}
#newRec table td{padding:5px;}
#mainContentWrap section {float: none;width: 100%;padding:0px !important;}
#toggleButton{cursor:pointer;}
select { margin: 0; background: #fff; outline: none; display: inline-block;cursor: pointer; -webkit-appearance: none; -moz-appearance: none;text-indent: 0.01px;}
.myDialogContentMain div{width:80%;margin:10px auto;}
.myDialogContentMain h2{text-align:center;margin:10px 0px;}
.myDialog input, .myDialog select, .myDialog textarea, .myDialog button { width: 100% !important; margin: 0px auto 10px; padding: 5px; border-radius: 5px; border: 1px solid grey;}
.formSubmit{margin:20px 0px !important; cursor:pointer;}
.zebra tbody tr td a{display:block;padding:5px;margin:5px 0px;}
.zebra tbody tr td a:nth-child(2){color:red !important;}
.zebra thead tr th:nth-child(4) {width: 5%;}
.zebra thead tr th:nth-child(5) {width: 3%;}
.zebra thead tr th:nth-child(4), .zebra tbody tr td:nth-child(4),.zebra tbody tr td:nth-child(5) {text-align:center !important;}
.error{text-align:center !important; width:100% !important;margin:10px 0px;}
</style>
</head>
<body>
 <header id="head"><?php require_once('inc/head.php'); ?></header>
 <div id="wrapMain">
   <div id="mainContent" class="wrapContent">
    <div id="mainContentWrap">
      <?php if($_SESSION['id_user']){?>
      <section id="toggleForm">
        <header id="toggleButton">Inserisci un nuovo toponimo</header>
        <div id="toggleDiv">
          <div id="newRec">
            <form name="toponimoForm" action="#">
              <label>Comune</label>
              <input type="search" id="comune" class="comuneToken"  name="comune" data-campo="comuneGid" placeholder="Inizia a digitare il nome del Comune..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')" oninput="setCustomValidity('')"/>
              <input type="hidden" id="comuneGid" name="comuneGid" value=""/>
              <label>Località</label>
              <select name="localita" id="localita" disabled required></select>
              <label>Toponimo</label>
              <input type="text" name="toponimo" value='' placeholder="Inserisci almeno 4 caratteri" required disabled >
              <input type="submit" class="submit" name="addTopo" value="Aggiungi toponimo" disabled>
              <div id="msg"></div>
            </form>
          </div>
        </div>
      </section>
      <section id="tabella">
        <header><span lang="it"><?php echo $header; ?></span></header>
        <div id="filtri">
          <input type="search" placeholder="cerca toponimo" id="filtro">
          <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
          <a href="#" class="export" id="csv" title="esporta dati tabella in formato csv">CSV</a>
        </div>
        <table class="zebra footable toggle-arrow" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
          <thead>
            <tr class='csv'>
              <th>Comune</th>
              <th>Località</th>
              <th>Toponimo</th>
              <th>Siti</th>
              <th data-sort-ignore="true"></th>
            </tr>
          </thead>
        <tbody>
         <?php
           while($row = pg_fetch_array($result)){
               echo "<tr class='csv'>";
               echo "<td>".$row['comune']."</td>";
               echo "<td>".$row['localita']."</td>";
               echo "<td>".$row['toponimo']."</td>";
               echo "<td>".$row['siti']."</td>";
               echo "<td>";
               if($_SESSION['classe'] == 1){echo "<a href='#' data-idtopo='".$row['id_toponimo']."' class='modTopo' title='modifica toponimo'><i class='fa fa-wrench'></i></a>";}
               if($_SESSION['classe'] == 1){echo "<a href='#' data-idtopo='".$row['id_toponimo']."' class='delTopo' title='elimina toponimo'><i class='fa fa-times'></i></a>";}
               echo "</td>";
               echo "</tr>";
          }
         ?>
        </tbody>
        <tfoot class="hide-if-no-paging">
              <tr>
               <td colspan="5">
                <div class="pagination pagination-centered"></div>
               </td>
              </tr>
             </tfoot>
       </table>
       </section>
       <?php }else{require_once("inc/noaccess.php");} ?>
       </div>
 <div id="nav">
 <aside>
  <section id="loginWrap">
  <?php
   if(isset($_SESSION['id_user'])){include_once('inc/usrmenu.php'); }
   else{include_once('inc/login_form.php');}
  ?>
  </section>

 <section id="navLink">
      <header><h1><i class="fa fa-link"></i> Link</h1></header>
      <nav class="navLink"><?php include_once('inc/link.php'); ?></nav>
     </section>
 </aside>
 </div><!-- right-nav -->
 </div><!-- colRight -->
 <div style="clear:both !important;"></div>

 </div>
  <footer><?php require_once("inc/footer.php"); ?></footer>


 <div class="myDialog">
     <div class="myDialogWrapContent">
         <div class="myDialogContent">
             <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
             <div class="myDialogContentMain">
               <div class="hidden" id="topoUpdateForm">
                 <h2>Modifica il seguente toponimo presente nel comune di <span class="comuneUp"></span>, in località <span class="locUp"></span></h2>
                 <input type="search" name="topoUp" placeholder="Inserisci il nome del toponimo..." >
                 <input type="hidden" name="topoIdUp">
                 <input type="submit" class="submit" name="upTopo" value="Modifica toponimo">
                 <div id="msgUp"></div>
               </form>
             </div>
             <div class="hidden" id="topoDelForm">
               <p class="error">Stai per eliminare il seguente toponimo</p>
               <p id="topoDel"></p>
               <p class="error">Se confermi, il toponimo verrà definitivamente eliminato</p>
               <input type="hidden" name="topoIdDel">
               <input type="submit" class="submit" name="delTopoSubmit" value="Conferma eliminazione">
               <div id="msgDel"></div>
             </div>
           </div>
         </div>
     </div>
 </div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.sort.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.paginate.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.filter.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  $('.footable').footable();
  $('.clear-filter').click(function (e) {
    e.preventDefault();
    $("#filtri span").text('');
    $('.footable').trigger('footable_clear_filter');
   });
   $("#csv").click(function (event) { exportTableToCSV.apply(this, [$('.zebra'), 'toponimi.csv']); });

   $("form[name=toponimoForm]").on("submit", function(e){
     e.preventDefault();
     var data = $(this).serialize();
     $.ajax({
       url:"inc/toponimo_insert.php",
       type:"POST",
       data:data,
       success: function(data){
         if(data=='ok'){
           $("#msg").text("ok, toponimo inserito").delay(3000).fadeOut('fast', function(){location.reload();} );
         }else{
           $("#msg").text("Attenzione, salvataggio fallito: "+data);
         }
       }
     });
   });

   $( ".comuneToken" ).autocomplete({
      source: "inc/comuneToken.php",
      minLength: 2,
      select: function( event, ui ) {
        event.preventDefault();
        $(this).val(ui.item.value);
        var c = $(this).data("campo");
        $("#"+c).val(ui.item.id);
        $.ajax({
         type: "POST",
         url: "inc/dinSelLocalita.php",
         data: {id:ui.item.id},
         cache: false,
         success: function(html){
           $("select[name=localita]")
            .removeAttr('disabled')
            .html(html)
            .on("change", function(){
                $("input[name=toponimo]").removeAttr('disabled').on("keyup", function(){
                  var i = $(this).val().length;
                  i > 3 ? $("input[name=addTopo]").removeAttr('disabled') : $("input[name=addTopo]").attr('disabled', true);
                });
            });
          }
        });//ajax
      }
    });

    $('#comune').on('click', function(){
      $('#comune').on('search keyup', function(){
        if(!this.value){
          $('select[name=localita]').val('').prop("disabled", true);
          $('input[name=toponimo]').val('').prop("disabled", true);
          $('input[name=addTopo]').prop("disabled", true);
        }
      });
      setTimeout(function() { $('#comune').off('search');}, 1);
    });

  $(".modTopo").click(function(){
    var idTopo = $(this).data('idtopo');
    var com = $(this).parent('td').siblings().eq(0).text();
    var loc = $(this).parent('td').siblings().eq(1).text();
    var topo = $(this).parent('td').siblings().eq(2).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $(".comuneUp").text(com);
      $(".locUp").text(loc);
      $("input[name=topoUp]").val(topo);
      $("input[name=topoIdUp]").val(idTopo);
      $("#topoUpdateForm").show();
      $("input[name=upTopo]").on("click", function(e){
        e.preventDefault();
        var id = $("input[name=topoIdUp]").val();
        var topo = $("input[name=topoUp]").val();
        if(!topo){
          $("#msgUp").text("Attenzione, il campo non può essere vuoto.");
          $("input[name=topoUp]").addClass("errorClass");
        }else{
          $("#msgUp").text("");
          $("input[name=topoUp]").removeClass("errorClass");
          $.post("inc/toponimo_update.php"
            , {id:id, topo:topo}
            , function(data){
                if(data=='ok'){
                  $("#msgUp").text("ok, toponimo aggiornato").delay(3000).fadeOut('fast', function(){location.reload();} );
                }else{
                  $("#msgUp").text("Attenzione, salvataggio fallito: "+data);
                }
            }
          );
        }
      });
    });
  });
  $(".delTopo").click(function(){
    var idTopo = $(this).data('idtopo');
    var topo = $(this).parent('td').siblings().eq(2).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $("p#topoDel").text(topo).css({"text-align":"center", "font-size":"1.2rem","font-weight":"bold"});
      $("#topoDelForm").show();
      $("input[name=delTopoSubmit]").click(function(){
        $.post("inc/toponimo_delete.php"
          , {id:idTopo}
          , function(data){
              if(data=='ok'){
                $("#msgDel").text("ok, toponimo definitivamente eliminato").delay(3000).fadeOut('fast', function(){location.reload();} );
              }else{
                $("#msgDel").text("Attenzione, eliminazione fallita: "+data);
              }
          }
        );
      });
    });
  });
});
</script>
</body>
</html>
