{
"etag":"\"a3ba5825-da06-d7bf-f810d06e148c14b2\"",
"type":"text\/javascript",
"exp":"0",
"desc":"",
"data":"$(function(){\n  if ( maintab ) {\n      $('#maintab > div > div.co-TabChild').removeClass('selected');\n      $('#maintab > div > div.'+maintab).addClass('selected');\n  }\n  if ( subtab ) {\n      $('#subtab > div > div.co-TabChild').removeClass('selected');\n      $('#subtab > div > div.'+subtab).addClass('selected');\n  }\n    $('#subtab > div >  div.co-TabChild a').each(function(){\n      var href = $(this).attr('href');\n      href += location.search;\n\t$(this).attr('href',href);\n    });\n  if ( viewtab ) {\n      $('div.viewtab > div > div.co-TabChild').removeClass('selected');\n      $('div.viewtab > div > div.'+viewtab).addClass('selected');\nconsole.log('div.viewtab > div > div.'+viewtab);\nconsole.log($('div.viewtab > div > div.'+viewtab));\n  }\n    $('div.viewtab > div >  div.co-TabChild a').each(function(){\n      var href = $(this).attr('href');\n      href += location.search;\n\t$(this).attr('href',href);\n    });\n});\n",
"_u":"js\/cockatoo.js"
}