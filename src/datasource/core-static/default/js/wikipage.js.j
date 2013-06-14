{
"etag":"\"2eeca9af-2d39-a749-a0293c0f2e3b25b3\"",
"type":"text/javascript",
"exp":"60",
"desc":"",
"data":"$( function () {\r
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
  $('div.wikieditlink').click(function(){\r
    $(this).next('div.wikiedit').slideDown();\r
    image_list();\r
  });\r
\r
\r
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
  $('form.wikieditaction').each(function(){\r
    var editAction = $(this);\r
    var url = editAction.attr('action');\r
      // SAVE\r
    editAction.find('input[value=\"save\"]').click(function(){\r
\t$.ajax({\r
\t  url: url,\r
\t  type: 'POST',\r
\t  dataType: 'JSON',\r
\t  data: {\r
\t    op: 'save',\r
\t    page: editAction.attr('pagename'),\r
\t    origin: editAction.find('textarea[name=\"origin\"]').val()\r
\t  },\r
\t  success: function(ret,st,xhr){\r
\t    window.location=window.location.pathname;\r
\t  }\r
\t});\r
    });\r
  \r
    // PREVIEW\r
    editAction.find('input[value=\"preview\"]').click(function(){\r
\t$.ajax({\r
\t  url: url,\r
\t  type: 'POST',\r
\t  dataType: 'JSON',\r
\t  data: {\r
\t    op: 'preview',\r
\t    page: editAction.attr('pagename'),\r
\t    origin: editAction.find('textarea[name=\"origin\"]').val()\r
\t  },\r
\t  success: function(ret,st,xhr){\r
\t    $('#'+editAction.attr('for')).html(page(ret.page.contents));\r
\t  }\r
\t});\r
    });  \r
  });\r
  // IMAGE\r
  $('form.wikiimageaction').each(function(){\r
    var imageAction = $(this);\r
    var images = imageAction.find('div.wikieditimages');\r
    var url = imageAction.attr('action');\r
    function image_list () {\r
      \r
\t$.ajax({\r
\t  url: url,\r
\t  type: 'POST',\r
\t  dataType: 'JSON',\r
\t  data: {\r
\t    op: 'flist',\r
\t    page: imageAction.attr('pagename')\r
\t  },\r
\t  success: function(ret,st,xhr){\r
\t    images.find('> table > tbody').empty();\r
\t    var tbody = images.find('> table > tbody');\r
\t    for ( var i in ret ) {\r
\t      var tr = $('<tr />');\r
\t\t$('<td class=\"img\" />')\r
\t\t.append( $('<img />')\r
\t\t\t.attr('src',ret[i]))\r
\t\t.appendTo(tr);\r
\t\t$('<td class=\"name\" />')\r
\t\t.text(i)\r
\t\t.appendTo(tr);\r
\t\t$('<td class=\"format\" />')\r
\t\t.text('&ref('+i+');')\r
\t\t.appendTo(tr);\r
\t\t$('<td class=\"check\" />')\r
\t\t.html('<input class=\"del\" type=\"button\" name=\"'+i+'\" value=\"delete\" />')\r
\t\t.appendTo(tr);\r
\t      tr.appendTo(tbody);\r
\t    }\r
            images.find('input.del').click(function(){\r
\t\t$.ajax({\r
\t\t  url: url,\r
\t\t  type: 'POST',\r
\t\t  dataType: 'JSON',\r
\t\t  data: {\r
\t\t    op: 'fdelete',\r
\t\t    page: imageAction.attr('pagename'),\r
\t\t    filename: $(this).attr('name')\r
\t\t  },\r
\t\t  success: function(ret,st,xhr){\r
\t\t    image_list();\r
\t\t  }\r
\t\t})\r
\t    });\r
\t  }\r
\t});\r
    }\r
    imageAction.find('input[name=\"upload\"]').click(function(){\r
      var url = imageAction.attr('action');\r
      imageAction.find('input[name=\"filename\"]').upload( \r
\t\t\t\t\t\t\turl,\r
\t\t\t\t\t\t\t{\r
\t\t\t\t\t\t\t  op: 'fupload',\r
\t\t\t\t\t\t\t  page: imageAction.attr('pagename')\r
\t\t\t\t\t\t\t},\r
\t\t\t\t\t\t\tfunction(res) {\r
\t\t\t\t\t\t\t  image_list();\r
\t\t\t\t\t\t\t}, '');\r
    });\r
  });\r
});",
"_u":"js/wikipage.js"
}