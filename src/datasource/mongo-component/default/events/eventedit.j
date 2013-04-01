{
"@R":"1364805425",
"type":"HorizontalWidget",
"subject":"eventedit",
"description":"",
"css":"#eventedit th {\r\n  font-size: 0.8em;\r\n  line-height: 0.8;\r\n}\r\n#eventedit input,\r\n#eventedit textarea {\r\n  font-size: 0.8em;\r\n}\r\n#eventedit input[type=\"text\"] {\r\n  width : 500px;\r\n}\r\n#eventedit textarea {\r\n  width  : 500px;\r\n  height : 100px;\r\n}\r\n\r\n\r\n",
"js":"$(function() {\r\n  $('#eventedit input[name=\"date\"]').datepicker({'dateFormat':'yy-mm-dd'} );\r\n  $('#eventedit input[name=\"capacity\"]').change(function(){\r\n    var v = $(this).val();\r\n    $(this).prev('div').remove();\r\n    if ( v < N_ATTENDERS ) {\r\n      $(this).parents('form').find('input[type=\"submit\"]').attr('disabled','disabled');\r\n      $(this).before('<div style=\"color:red\">\u73fe\u53c2\u52a0\u8005\u6570\u3088\u308a\u5c11\u306a\u3044\u6307\u5b9a\u306f\u3067\u304d\u307e\u305b\u3093<\/div>');\r\n    }else {\r\n      $(this).parents('form').find('input[type=\"submit\"]').removeAttr('disabled');\r\n    }\r\n  });\r\n});",
"id":"eventedit",
"class":"page",
"body":"<?cs if: A.mongo.event.writable ?>\r\n<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u30a4\u30d9\u30f3\u30c8\u5185\u5bb9<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n<form method=\"POST\" action=\"<?cs var:C._base ?>\/events\/edit\/<?cs var:A.mongo.event.docid ?>\">\r\n  <table><tbody>\r\n    <tr>\r\n    <th>\u516c\u958b<\/th>\r\n    <td><input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.event.public ?>checked<?cs \/if ?>><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u958b\u50ac\u65e5<\/th>\r\n    <td><input type=\"text\" name=\"date\" value=\"<?cs var:A.mongo.event.date ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u6642\u9593<\/th>\r\n    <td><input type=\"text\" name=\"time\" value=\"<?cs var:A.mongo.event.time ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u4f1a\u5834\uff08\u4f4f\u6240\u7b49 google map\u9023\u643a\uff09<\/th>\r\n    <td><input type=\"text\" name=\"address\"  value=\"<?cs var:A.mongo.event.address ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u5b9a\u54e1<\/th>\r\n    <td><input type=\"text\" name=\"capacity\"  value=\"<?cs var:A.mongo.event.capacity ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td><input type=\"text\" name=\"title\"  value=\"<?cs var:A.mongo.event.title ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30b5\u30d6\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td><input type=\"text\" name=\"subtitle\"  value=\"<?cs var:A.mongo.event.subtitle ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>URL<\/th>\r\n    <td><input type=\"text\" name=\"url\"  value=\"<?cs var:A.mongo.event.url ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u6982\u8981<\/th>\r\n    <td><textarea name=\"origin\"><?cs var:A.mongo.event.origin ?><\/textarea><\/td>\r\n    <\/tr><tr>\r\n    <th><\/th>\r\n    <td>\r\n    <input type=\"hidden\" name=\"docid\" value=\"<?cs var:A.mongo.event.docid ?>\"><\/input>\r\n    <input type=\"submit\" name=\"op\" value=\"save\"><\/input>\r\n    <input type=\"submit\" name=\"op\" value=\"preview\"><\/input>\r\n    <a target=\"_blank\" href=\"<?cs var:C._base ?>\/notation\">notation<\/a>\r\n    <\/td>\r\n    <\/tr>\r\n  <\/tbody><\/table>\r\n<\/form>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<script>\r\n<?cs set: a = #0 ?><?cs each:item = A.mongo.event.attenders ?><?cs set: a = a+#1 ?><?cs \/each ?>\r\nvar N_ATTENDERS=<?cs var:a ?>;\r\n<\/script>\r\n<?cs \/if ?>\r\n",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text\/css\" media=\"all\" href=\"\/_s_\/core\/default\/css\/smoothness\/jquery-ui-1.8.21.custom.css\"><\/link>",
"bottom":"<script src=\"http:\/\/hirkubota:30080\/_s_\/core\/default\/js\/jquery-ui-1.8.21.custom.min.js\"><\/script>",
"_u":"events\/eventedit"
}