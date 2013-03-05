{
"@R":"1362474161",
"type":"HorizontalWidget",
"subject":"page",
"description":"page",
"css":"#page div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}",
"js":"$( function () {\r\n  $('div.ih > a.toggle').text('Hide indexes').addClass('visible');\r\n  $('div.ih > a.toggle').click(function(ev){\r\n    if ( $(this).hasClass('visible') ) {\r\n      $('div.ih li').slideUp();\r\n      $(this).removeClass('visible').text('View indexes');\r\n    }else{\r\n      $('div.ih li').slideDown();\r\n      $(this).addClass('visible').text('Hide indexes');\r\n    }\r\n  });\r\n});",
"id":"page",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<?cs each:item = A.mongo.page.contents ?>\r\n  <?cs call:drawTags(item)?>\r\n<?cs \/each ?>\r\n<\/div>\r\n<?cs if:S.login.root?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/edit\/<?cs var:A.mongo.page.title ?>\">\u7de8\u96c6<\/a><\/div>\r\n<?cs \/if ?>\r\n<\/div>\r\n",
"action":[
""
],
"_u":"w\/page",
"header":"",
"bottom":""
}