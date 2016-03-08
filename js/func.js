var lang = new Lang('it');
lang.dynamic('en', 'js/lang/js/langpack/en.json');
var headH = $("#head nav").outerHeight(true);
var windowX =  $( window ).width();
var windowY =  $( window ).height();

$(document).ready(function() {
 $("#logo").css("height",headH + "px");
 $(".myDialogContentHeader i").click(function(){$(".myDialog").fadeOut('fast', function(){scroll();});});
 $('#badge img').bind('mouseenter mouseleave', function() {$(this).attr({src: $(this).attr('data-hover') , 'data-hover': $(this).attr('src') });});
 $('#toggleDiv').hide();
 $('#toggleButton').click(function(){ $('#toggleDiv').slideToggle('fast'); });
 $('.closeDialog').click(function(){$(this).closest('.ui-dialog-content').dialog('close');});
 $('.modLink').click(function(event){ event.preventDefault(); });
 $("#enLang").click(function(){window.lang.change('en'); return false;});
 $("#itLang").click(function(){window.lang.change('it'); return false;});
 
 (function ($) {
    $.fn.clickToggle = function (func1, func2) {
        var funcs = [func1, func2];
        this.data('toggleclicked', 0);
        this.click(function () {
            var data = $(this).data();
            var tc = data.toggleclicked;
            $.proxy(funcs[tc], this)();
            data.toggleclicked = (tc + 1) % 2;
        });
        return this;
    };
  }(jQuery));
});
//*****************************************************************************//
 function exportTableToCSV($table, filename) {
  var $rows = $table.find('tr.csv'),
  tmpColDelim = String.fromCharCode(11), // vertical tab character
  tmpRowDelim = String.fromCharCode(0), // null character
  colDelim = '","',
  rowDelim = '"\r\n"',
  csv = '"' + $rows.map(function (i, row) {
    var $row = $(row), $cols = $row.find('th, td');
    return $cols.map(function (j, col) {
      var $col = $(col), text = $col.text();
      return text.replace(/"/g, '""'); // escape double quotes
    }).get().join(tmpColDelim);
  })
  .get().join(tmpRowDelim)
  .split(tmpRowDelim)
  .join(rowDelim)
  .split(tmpColDelim)
  .join(colDelim) + '"',
  // Data URI
  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

  $(this).attr({
    'download': filename,
    'href': csvData,
    'target': '_blank'
  });
 }

function scroll() {
    document.documentElement.style.overflow = 'auto';  // firefox, chrome
    document.body.scroll = "yes"; // ie only
}

function noScroll() {
    document.documentElement.style.overflow = 'hidden';  // firefox, chrome
    document.body.scroll = "no"; // ie only
}
function maximFoto(foto){
    noScroll();
    var top = $(document).scrollTop();console.log(top);
    var src = foto.attr('src');
    var cap = foto.attr('title');
    var preImg = new Image();
    preImg.src = src;
    var img = $("<img />");
    img.attr("src",src);
    var w = preImg.width;
    var h = preImg.height;
    var maxW = windowX -(windowX * 0.15);
    var maxH = windowY -(windowY * 0.15);
    if(w >= h){
        ratio = maxW / w;
        heightDef = h * ratio;
        if (heightDef > maxH) {
            ratio = maxH / heightDef;
            widthDef = maxW * ratio;
            $("#galleryWrap").css({"width":widthDef});
            img.css({"width":widthDef, "height":maxH});
        }else{
            $("#galleryWrap").css({"width":maxW});
            img.css({"width":maxW, "height":heightDef});
        }
    }else{
        ratio = maxH / h;
        widthDef = w * ratio;
        $("#galleryWrap").css({"width":widthDef});
        img.css({"width":widthDef, "height":maxH});
    }
    $("#fotoContent").html(img);
    $("#caption").text(cap);
    $("#galleryDiv").css({"top":top+"px"}).fadeIn('fast');
    $("#galleryWrap header i").click(function(){
       $("#galleryDiv").fadeOut('fast');
       $("#fotoContent").html('');
       scroll();
    });
}
