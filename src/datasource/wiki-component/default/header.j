{
"@R":"1334044329",
"type":"HorizontalWidget",
"subject":"header",
"description":"header",
"css":"#header  div.a-Wbody {\n}\n#header  ul {\n  overflow: hidden;\n}\n#header  ul > li {\n  list-style-image: none;\n  list-style-position: outside;\n  list-style-type: none;\n  float: left;\n  padding: 0 10px 0 10px;\n}",
"js":"$( function () {\n  $('#new').click(function(e){\n     $('#hform').html('<form method=\"GET\" action=\"'+$(this).attr('action')+'\">Input page name : <input type=\"text\" value=\"\" name=\"page\"><\/input><\/form>');\n  });\n  $('#upload').click(function(e){\n     $('#hform').html('<form method=\"POST\" enctype=\"multipart\/form-data\" action=\"'+$(this).attr('action')+'\">FIlename : <input id=\"fupload\" type=\"file\" value=\"\" name=\"filename\"><input type=\"submit\" value=\"save\" name=\"op\"><\/input><\/form>');\n  });\n\n  $('#move').click(function(e){\n     $('#hform').html('<form method=\"POST\" action=\"'+$(this).attr('action')+'\">New page name : <input type=\"text\" value=\"\" name=\"new\"><\/input><input type=\"hidden\" name=\"op\" value=\"move\"><\/input><\/form>');\n  });\n  $('#jump').click(function(e){\n     $('#hform').html('<form method=\"GET\" action=\"'+$(this).attr('action')+'\">Input page name : <input type=\"text\" value=\"\" name=\"page\"><\/input><\/form>');\n  });\n});\n",
"id":"header",
"class":"",
"body":"<ul>\n<li><a id=\"top\" href=\"\/wiki\/view\">top<\/a><\/li>\n<?cs if: ?S.login.user ?>\n<li><a id=\"new\" href=\"#\" action=\"\/wiki\/edit\">new<\/a><\/li>\n<li><a id=\"edit\" href=\"\/wiki\/edit\/<?cs var:A.wiki.page?>\">edit<\/a><\/li>\n<li><a id=\"move\" href=\"#\" action=\"\/wiki\/edit\/<?cs var:A.wiki.page?>\">move<\/a><\/li>\n<li><a id=\"upload\" href=\"#\" action=\"\/wiki\/upload\/<?cs var:A.wiki.page?>\">upload<\/a><\/li>\n<li><a id=\"list\" href=\"\/wiki\/uploaded\/<?cs var:A.wiki.page?>\">files<\/a><\/li>\n<?cs else ?>\n<li>new<\/li>\n<li>edit<\/li>\n<li>move<\/li>\n<li>upload<\/li>\n<li>uploaded<\/li>\n<?cs \/if ?>\n<li><a id=\"jump\" href=\"#\" action=\"\/wiki\/view\">jump<\/a><\/li>\n<li><a id=\"reset\" href=\"\/wiki\/about\">about-wiki<\/a><\/li>\n<div id=\"hform\" style=\"clear:both\"><\/div>\n<\/ul>\n<hr \/>\n",
"action":[
""
],
"_u":"header"
}