{
"@R":"1371114040",
"type":"HorizontalWidget",
"subject":"wikipage",
"description":"",
"css":"",
"js":"$( function () {\r
  // INDEX\r
  $('div.ih > a.toggle').text('Hide indexes').addClass('visible');\r
  $('div.ih > a.toggle').click(function(ev){\r
    if ( $(this).hasClass('visible') ) {\r
      $('div.ih li').slideUp();\r
      $(this).removeClass('visible').text('View indexes');\r
    }else{\r
      $('div.ih li').slideDown();\r
      $(this).addClass('visible').text('Hide indexes');\r
    }\r
  });\r
  // EDIT\r
  $('#wikieditlink').click(function(){\r
    $('#wikiedit').slideDown();\r
    image_list();\r
  });\r
\r
  // SAVE\r
  var editAction = $('#wikieditaction');\r
  $('#wikieditaction input[value=\"save\"]').click(function(){\r
    var url = editAction.attr('action');\r
      $.ajax({\r
\turl: url,\r
\ttype: 'POST',\r
\tdataType: 'JSON',\r
\tdata: {\r
\t  op: 'save',\r
\t  page: editAction.attr('pagename'),\r
\t  origin: editAction.find('textarea[name=\"origin\"]').val()\r
\t},\r
\tsuccess: function(ret,st,xhr){\r
\t  window.location=window.location.pathname;\r
\t}\r
      });\r
  });\r
  function page(obj){\r
    var ret = '';\r
    for ( var i in obj ) {\r
      var elem = obj[i];\r
      var attr = '';\r
      for ( var a in elem.attr ) {\r
\tattr += a+'=\"'+elem.attr[a]+'\" '\r
      }\r
      if( elem.tag === 'text' ) {\r
\tret += (elem.text)?elem.text:'';\r
\tret += page(elem.children);\r
      }else if( elem.tag === 'br' ) {\r
\tret += '<br />';\r
      }else{\r
\tret += '<'+elem.tag+' '+attr+'>' + page(elem.children) + '</'+elem.tag+'>';\r
      }\r
    }\r
    return ret;\r
  }\r
  // PREVIEW\r
  $('#wikieditaction input[value=\"preview\"]').click(function(){\r
    var url = editAction.attr('action');\r
      $.ajax({\r
\turl: url,\r
\ttype: 'POST',\r
\tdataType: 'JSON',\r
\tdata: {\r
\t  op: 'preview',\r
\t  page: editAction.attr('pagename'),\r
\t  origin: editAction.find('textarea[name=\"origin\"]').val()\r
\t},\r
\tsuccess: function(ret,st,xhr){\r
\t    $('#wikipage').html(page(ret.page.contents));\r
\t}\r
      });\r
  });  \r
  // IMAGE\r
  var imageAction = $('#wikiimageaction');\r
  function image_list () {\r
    var url = imageAction.attr('action');\r
    $.ajax({\r
      url: url,\r
      type: 'POST',\r
      dataType: 'JSON',\r
      data: {\r
\top: 'flist',\r
\tpage: imageAction.attr('pagename')\r
      },\r
      success: function(ret,st,xhr){\r
\t$('#wikieditimages > table > tbody').empty();\r
\tvar tbody = $('#wikieditimages > table > tbody');\r
\tfor ( var i in ret ) {\r
\t  var tr = $('<tr />');\r
\t    $('<td class=\"img\" />')\r
\t    .append( $('<img />')\r
\t\t    .attr('src',ret[i]))\r
\t    .appendTo(tr);\r
\t    $('<td class=\"name\" />')\r
\t    .text(i)\r
\t    .appendTo(tr);\r
\t    $('<td class=\"format\" />')\r
\t    .text('&ref('+i+');')\r
\t    .appendTo(tr);\r
\t    $('<td class=\"check\" />')\r
\t    .html('<input class=\"del\" type=\"button\" name=\"'+i+'\" value=\"delete\" />')\r
\t    .appendTo(tr);\r
\t  tr.appendTo(tbody);\r
\t}\r
        $('#wikieditimages input.del').click(function(){\r
\t    $.ajax({\r
\t      url: url,\r
\t      type: 'POST',\r
\t      dataType: 'JSON',\r
\t      data: {\r
\t\top: 'fdelete',\r
\t\tpage: imageAction.attr('pagename'),\r
\t\tfilename: $(this).attr('name')\r
\t      },\r
\t      success: function(ret,st,xhr){\r
\t\timage_list();\r
\t      }\r
\t    })\r
\t});\r
      }\r
    });\r
  }\r
  $('#wikiimageaction input[name=\"upload\"]').click(function(){\r
    var url = imageAction.attr('action');\r
    $('#wikiimageaction input[name=\"filename\"]').upload( \r
                                 url,\r
\t\t\t\t {\r
                                   op: 'fupload',\r
                                   page: imageAction.attr('pagename')\r
                                 },\r
\t\t\t\t function(res) {\r
\t\t\t\t   image_list();\r
\t\t\t\t }, '');\r
    });\r
});",
"id":"",
"class":"",
"body":"<?cs def:drawTags(item) ?><?cs if:item.tag=='text'?><?cs var:item.text?><?cs each:child=item.children?><?cs call:drawTags(child)?><?cs /each ?><?cs elif:item.tag=='br' ?><br><?cs else ?><<?cs var:item.tag?><?cs each:attr = item.attr ?> <?cs name:attr?>=\"<?cs var:attr?>\"<?cs /each ?>><?cs each:child = item.children ?><?cs call:drawTags(child)?><?cs /each ?></<?cs var:item.tag?>><?cs /if ?><?cs /def ?>\r
<?cs def:drawPage(id,page) ?><div id=\"<?cs var:id?>\" class=\"wikipage\"><div class=\"credit\">by <?cs var:page._ownername ?><time><?cs var:page._timestr ?></time></div><?cs each:item = page.contents ?><?cs call:drawTags(item)?><?cs /each ?></div><?cs /def ?>\r
<?cs def:drawEdit(id,page,editpath,notation) ?><div class=\"wikieditlink\"><a>\u7de8\u96c6</a></div><div class=\"wikiedit\"><form class=\"wikieditaction\" for=\"<?cs var:id?>\" action=\"<?cs var:editpath?>\" pagename=\"<?cs var: page.title?>\"><textarea name=\"origin\"><?cs var:page.origin ?></textarea><div><input type=\"button\" name=\"op\" value=\"preview\"></input><input type=\"button\" name=\"op\" value=\"save\"></input><a target=\"_blank\" href=\"<?cs var:notation ?>\">notation</a></div></form><form class=\"wikiimageaction\" for=\"<?cs var:id?>\" action=\"<?cs var:editpath?>\" pagename=\"<?cs var: page.title?>\"><input type=\"file\" name=\"filename\" value=\"\" /><input type=\"button\" name=\"upload\" value=\"upload\" /><div class=\"wikieditimages\"><table><thead><tr><td>image</td><td>name</td><td>format</td><td>action</td><tr></thead><tbody /></table></div></form></div><?cs /def ?>\r
",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/core/default/css/wikipage.css\" />\r
",
"bottom":"<script type=\"text/javascript\" src=\"/_s_/core/default/js/jquery.upload-1.0.2.js\"></script>\r
<script type=\"text/javascript\" src=\"/_s_/core/default/js/wikipage.js\"></script>\r
\r
",
"_u":"utils/wikipage"
}