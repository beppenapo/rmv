<?php
session_start();
require_once("inc/db.php");

$query=("
SELECT m.id_microtoponimo AS id_micro, c.nome AS comune, l.localita, t.toponimo, m.microtoponimo, count(s.id) as siti
FROM liste.microtoponimo m
left join liste.toponimo t ON m.id_toponimo = t.id_toponimo
left join comuni c ON t.id_comune = c.gid
left join sito s ON s.id_microtoponimo = m.id_microtoponimo
left join liste.localita l ON t.id_localita = l.id_localita
where m.id_toponimo <> 12
group by m.id_microtoponimo, c.nome, l.localita, t.toponimo, m.microtoponimo
order by comune asc, localita asc, toponimo asc, microtoponimo asc;
");
$result=pg_query($connection, $query);
$r = pg_num_rows($result);
$header = (!$r) ? 'Non sono presenti microtoponimi nel database ' : 'Lista completa dei microtoponimi presenti nel database ('.$r.')';
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
.zebra thead tr th:nth-child(5) {width: 5%;}
.zebra thead tr th:nth-child(6) {width: 3%;}
.zebra thead tr th:nth-child(5), .zebra tbody tr td:nth-child(5),.zebra tbody tr td:nth-child(6) {text-align:center !important;}
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
        <header id="toggleButton">Inserisci un nuovo microtoponimo</header>
        <div id="toggleDiv">
          <div id="newRec">
            <form name="microtoponimoForm" action="#">
              <label>Comune</label>
              <input type="search" id="comune" class="comuneToken"  name="comune" data-campo="comuneGid" placeholder="Inizia a digitare il nome del Comune..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')" oninput="setCustomValidity('')"/>
              <input type="hidden" id="comuneGid" name="comuneGid" value=""/>
              <label>Località</label>
              <select name="localita" id="localita" disabled required></select>
              <label>Toponimo</label>
              <select name="toponimo" id="toponimo" disabled required></select>
              <label>Microtoponimo</label>
              <input type="text" name="microtoponimo" id="microtoponimo" disabled required>
              <input type="submit" class="submit" name="addMicro" value="Aggiungi microtoponimo" disabled>
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
              <th>Microtoponimo</th>
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
               echo "<td>".$row['microtoponimo']."</td>";
               echo "<td>".$row['siti']."</td>";
               echo "<td>";
               if($_SESSION['classe'] == 1){echo "<a href='#' data-idmicro='".$row['id_micro']."' class='modMicro' title='modifica microtoponimo'><i class='fa fa-wrench'></i></a>";}
               if($_SESSION['classe'] == 1){echo "<a href='#' data-idmicro='".$row['id_micro']."' class='delMicro' title='elimina microtoponimo'><i class='fa fa-times'></i></a>";}
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
  <footer><?php require_once("inc/footer_test.php"); ?></footer>


 <div class="myDialog">
     <div class="myDialogWrapContent">
         <div class="myDialogContent">
             <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
             <div class="myDialogContentMain">
               <div class="hidden" id="microUpdateForm">
                 <h2>Modifica il seguente microtoponimo presente nel comune di <span class="comuneUp"></span>, in località <span class="locUp"></span></h2>
                 <input type="search" name="microUp" placeholder="Inserisci il nome del microtoponimo..." >
                 <input type="hidden" name="microIdUp">
                 <input type="submit" class="submit" name="upMicro" value="Modifica microtoponimo">
                 <div id="msgUp"></div>
               </form>
             </div>
             <div class="hidden" id="microDelForm">
               <p class="error">Stai per eliminare il seguente microtoponimo</p>
               <p id="microDel"></p>
               <p class="error">Se confermi, il microtoponimo verrà definitivamente eliminato</p>
               <input type="hidden" name="microIdDel">
               <input type="submit" class="submit" name="delMicroSubmit" value="Conferma eliminazione">
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

   $("form[name=microtoponimoForm]").on("submit", function(e){
     e.preventDefault();
     var data = $(this).serialize();
     $.ajax({
       url:"inc/microtoponimo_insert.php",
       type:"POST",
       data:data,
       success: function(data){
         if(data=='ok'){
           $("#msg").text("ok, microtoponimo inserito").delay(3000).fadeOut('fast', function(){location.reload();} );
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
            .prop('disabled',false)
            .html(html)
            .on("change", function(){
              $.ajax({
               type: "POST",
               url: "inc/dinSelToponimo.php",
               data: {id:$(this).val()},
               cache: false,
               success: function(html){
                 $("select[name=toponimo]").prop('disabled',false).html(html);
                 if(!$("select[name=toponimo]").find(":selected").val()){
                   $("#msg").text("Attenzione, non esistono toponimi registrati per questa località, scegli un altro valore o prima inserisci un nuovo toponimo.");
                 }else{
                   $("select[name=toponimo]").on("change", function(){
                     $("input[name=microtoponimo]").removeAttr('disabled').on("keyup", function(){
                       var i = $(this).val().length;
                       i > 3 ? $("input[name=addMicro]").removeAttr('disabled') : $("input[name=addMicro]").attr('disabled', true);
                     });
                   });
                }
               }
            });
          });
        }
      });
      }
    });

    $('#comune').on('click', function(){
      $('#comune').on('search keyup', function(){
        if(!this.value){
          $('select[name=localita]').val('').prop("disabled", true);
          $('select[name=toponimo]').val('').prop("disabled", true);
          $('input[name=microtoponimo]').val('').prop("disabled", true);
          $('input[name=addMicro]').prop("disabled", true);
        }
      });
      setTimeout(function() { $('#comune').off('search');}, 1);
    });

  $(".modMicro").click(function(){
    var idMicro = $(this).data('idmicro');
    var com = $(this).parent('td').siblings().eq(0).text();
    var loc = $(this).parent('td').siblings().eq(1).text();
    var topo = $(this).parent('td').siblings().eq(2).text();
    var micro = $(this).parent('td').siblings().eq(3).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $(".comuneUp").text(com);
      $(".locUp").text(loc);
      $("input[name=microUp]").val(micro);
      $("input[name=microIdUp]").val(idMicro);
      $("#microUpdateForm").show();
      $("input[name=upMicro]").on("click", function(e){
        e.preventDefault();
        var id = $("input[name=microIdUp]").val();
        var micro = $("input[name=microUp]").val();
        if(!micro){
          $("#msgUp").text("Attenzione, il campo non può essere vuoto.");
          $("input[name=microUp]").addClass("errorClass");
        }else{
          $("#msgUp").text("");
          $("input[name=microUp]").removeClass("errorClass");
          $.post("inc/microtoponimo_update.php"
            , {id:id, micro:micro}
            , function(data){
                if(data=='ok'){
                  $("#msgUp").text("ok, microtoponimo aggiornato").delay(3000).fadeOut('fast', function(){location.reload();} );
                }else{
                  $("#msgUp").text("Attenzione, salvataggio fallito: "+data);
                }
            }
          );
        }
      });
    });
  });
  $(".delMicro").click(function(){
    var idMicro = $(this).data('idmicro');
    var micro = $(this).parent('td').siblings().eq(3).text();
    $(".myDialog").fadeIn('fast', function(){
      noScroll();
      $(".myDialogContent").css("height","auto");
      $("p#microDel").text(micro).css({"text-align":"center", "font-size":"1.2rem","font-weight":"bold"});
      $("#microDelForm").show();
      $("input[name=delMicroSubmit]").click(function(){
        $.post("inc/microtoponimo_delete.php"
          , {id:idMicro}
          , function(data){
              if(data=='ok'){
                $("#msgDel").text("ok, microtoponimo definitivamente eliminato").delay(3000).fadeOut('fast', function(){location.reload();} );
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
