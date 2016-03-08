<?php
session_start();
require_once("inc/db.php");

$query=("
SELECT localita.id_localita, localita.localita, comuni.gid as id_comune, comuni.nome as comune, count(poi.gid) as siti
FROM liste.localita
LEFT JOIN comuni ON localita.id_comune = comuni.gid
LEFT JOIN poi ON localita.id_localita = poi.id_localita
WHERE localita.id_localita != 15
GROUP BY localita.id_localita, localita.localita, comuni.id, comuni.nome
ORDER BY localita ASC;
");
$result=pg_query($connection, $query);
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
.zebra thead tr th:nth-child(3) {width: 5%;}
.zebra thead tr th:nth-child(4) {width: 3%;}
.zebra thead tr th:nth-child(3), .zebra tbody tr td:nth-child(3),.zebra tbody tr td:nth-child(4) {text-align:center !important;}
.error{text-align:center !important; width:100% !important;margin:10px 0px;}
</style>
</head>
<body>
 <header id="head"><?php require_once('inc/head.php'); ?></header>
 <div id="wrapMain">
   <div id="mainContent" class="wrapContent">
    <div id="mainContentWrap">
      <section id="toggleForm">
        <header id="toggleButton">Inserisci una nuova località</header>
        <div id="toggleDiv" style="<?php echo $classToggle; ?>">
          <div id="newRec">
            <form name="localitaForm" action="#">
              <label>Comune</label>
              <input type="search" id="comune" class="comuneToken"  name="comune" data-campo="comuneGid" placeholder="Inizia a digitare il nome del Comune..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
              oninput="setCustomValidity('')"/>
              <input type="hidden" id="comuneGid" name="comuneGid" value=""/>
              <label>Località</label>
              <input type="search" id="localita" name="localita" placeholder="Inserisci il nome della Località..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
              oninput="setCustomValidity('')"/>
              <input type="submit" class="submit" name="addLoc" value="Aggiungi località">
              <div id="msg"></div>
            </form>
          </div>
        </div>
      </section>
      <section id="tabella">
        <header><span lang="it">Lista completa delle località presenti nel database</span></header>
        <div id="filtri">
          <input type="search" placeholder="cerca localita" id="filtro">
          <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
          <a href="#" class="export" id="csv" title="esporta dati tabella in formato csv">CSV</a>
        </div>
        <table class="zebra footable toggle-arrow" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
          <thead>
            <tr class='csv'>
              <th>Comune</th>
              <th>Località</th>
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
               echo "<td>".$row['siti']."</td>";
               echo "<td>";
               if($_SESSION['id_user']== 17 || $row['classe'] != 1){echo "<a href='#' data-idloc='".$row['id_localita']."' class='modLoc' title='modifica localita'><i class='fa fa-wrench'></i></a>";}
               if($row['classe'] != 1){echo "<a href='#' data-idloc='".$row['id_localita']."' class='delLoc' title='elimina localita'><i class='fa fa-times'></i></a>";}
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
  <footer><?php require_once("inc/footer_test.php"); ?></footer>


 <div class="myDialog">
     <div class="myDialogWrapContent">
         <div class="myDialogContent">
             <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
             <div class="myDialogContentMain">
               <div class="hidden" id="locUpdateForm">
                 <h2>Modifica la seguente località presente nel comune di <span class="comuneUp"></span></h2>
                 <input type="search" name="locUp" placeholder="Inserisci il nome della Località..." >
                 <input type="hidden" name="locIdUp">
                 <input type="submit" class="submit" name="upLoc" value="Modifica località">
                 <div id="msgUp"></div>
               </form>
             </div>
             <div class="hidden" id="locDelForm">
               <p class="error">Stai per eliminare la seguente Località</p>
               <p id="locDel"></p>
               <p class="error">Se confermi la località verrà definitivamente eliminata</p>
               <input type="hidden" name="locIdDel">
               <input type="submit" class="submit" name="delLocSubmit" value="Conferma eliminazione">
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
   $("#csv").click(function (event) { exportTableToCSV.apply(this, [$('.zebra'), 'localita.csv']); });

   $("form[name=localitaForm]").on("submit", function(e){
     e.preventDefault();
     var data = $(this).serialize();
     $.ajax({
       url:"inc/localita_insert.php",
       type:"POST",
       data:data,
       success: function(data){
         if(data=='ok'){
           $("#msg").text("ok, località inserita").delay(3000).fadeOut('fast', function(){location.reload();} );
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
      }
    });

  $(".modLoc").click(function(){
    var idloc = $(this).data('idloc');
    var com = $(this).parent('td').siblings().eq(0).text();
    var loc = $(this).parent('td').siblings().eq(1).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $(".comuneUp").text(com);
      $("input[name=locUp]").val(loc);
      $("input[name=locIdUp]").val(idloc);
      $("#locUpdateForm").show();
      $("input[name=upLoc]").on("click", function(e){
        e.preventDefault();
        var id = $("input[name=locIdUp]").val();
        var loc = $("input[name=locUp]").val();
        if(!loc){
          $("#msgUp").text("Attenzione, il campo non può essere vuoto.");
          $("input[name=locUp]").addClass("errorClass");
        }else{
          $("#msgUp").text("");
          $("input[name=locUp]").removeClass("errorClass");
          $.post("inc/localita_update.php"
            , {id:id, loc:loc}
            , function(data){
                if(data=='ok'){
                  $("#msgUp").text("ok, località aggiornata").delay(3000).fadeOut('fast', function(){location.reload();} );
                }else{
                  $("#msgUp").text("Attenzione, salvataggio fallito: "+data);
                }
            }
          );
        }
      });
    });
  });
  $(".delLoc").click(function(){
    var idloc = $(this).data('idloc');
    var loc = $(this).parent('td').siblings().eq(1).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $("p#locDel").text(loc).css({"text-align":"center", "font-size":"1.2rem","font-weight":"bold"});
      $("#locDelForm").show();
      $("input[name=delLocSubmit]").click(function(){
        $.post("inc/localita_delete.php"
          , {id:idloc}
          , function(data){
              if(data=='ok'){
                $("#msgDel").text("ok, località definitivamente eliminata").delay(3000).fadeOut('fast', function(){location.reload();} );
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
