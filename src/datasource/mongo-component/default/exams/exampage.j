{
"@R":"1364794044",
"type":"HorizontalWidget",
"subject":"exampage",
"description":"",
"css":"#exampage li.correct {\r\n  color: #E00000;\r\n  font-weight: bold;\r\n}\r\n#exampage span.result {\r\n  color: #E00000;\r\n  font-weight: bold;\r\n  font-size: 2em;\r\n  vertical-align: bottom;\r\n}\r\n#exampage div.h3,\r\n#exampage div.hd3 {\r\n  display: none;\r\n}\r\n#exampage div.show{\r\n  display: block;\r\n}\r\n#exampage h3.q {\r\n  cursor: pointer;\r\n}\r\n#exampage div.q {\r\n  display: none;\r\n}\r\n#exampage div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#exampage table.details {\r\n  padding: 15px 0 0 10px;\r\n  min-width: 600px;\r\n  float:left;\r\n}\r\n#exampage table.details th {\r\n  min-width: 100px;\r\n}\r\n#map {\r\n  float:left;\r\n  height: 200px;\r\n  width: 200px;\r\n  border: 1px solid #333;\r\n  margin-top: 0.6em;\r\n}",
"js":"$(function(){\r\n  var CUR=0;\r\n  $('#goexam').click(function(ev){\r\n    CUR=0;\r\n    showQ(0);\r\n  });\r\n  $('#exampage button.gonext').click(function(ev){\r\n    CUR++;\r\n    showQ(CUR);\r\n  });\r\n  function showQ( i){\r\n      $('#exampage div.h3').hide();\r\n      $('#exampage div.hd3').hide();\r\n      $('#q'+i).show();\r\n      $('#q'+i).next().show();\r\n  }\r\n});",
"id":"exampage",
"class":"page",
"body":"<div class=\"mongo\">\r\n  <div class=\"window\" style=\"width:100%;clear:both;\">\r\n    <div class=\"credit\">by <?cs var:A.mongo.exam._ownername ?> <time><?cs var:A.mongo.exam._timestr ?><\/time><\/div>\r\n    <div class=\"h1\"><h1><?cs var:A.mongo.exam.qname?><\/h1><\/div>\r\n    <div class=\"hd1\">\r\n      <div class=\"hd2\">\r\n\t<form method=\"POST\" action=\"\">\r\n          <?cs var:A.mongo.exam.qsummary ?>\r\n          <br>\r\n          <?cs if: ! A.mongo.exam.done ?>\r\n            <button id=\"goexam\" type=\"button\">\u30c6\u30b9\u30c8\u958b\u59cb<\/button>\r\n          <?cs elif:A.mongo.exam.score != null ?>\r\n            <br>\r\n\t    <span class=\"result\"><?cs var: A.mongo.exam.score ?>\u70b9<\/span>\r\n          <?cs \/if ?>\r\n          <?cs set: i = 0 ?>\r\n  \t<?cs each: question = A.mongo.exam.qs ?>\r\n          <?cs set: i = i+1 ?>\r\n          <div id=\"q<?cs name:question ?>\" class=\"h3 <?cs var:question.show ?>\"><h3 class=\"q\">Q<?cs var:i?>.\r\n              <?cs if: A.mongo.exam.done ?>\r\n                <?cs if: question.correct != null ?>\r\n\t        <span class=\"result\">\r\n\t\t  <?cs if: question.checked == question.correct ?>\r\n\t\t  \u25cb\r\n  \t\t  <?cs else ?>\r\n\t\t  \u00d7\r\n\t\t  <?cs \/if ?>\r\n\t\t<\/span>\r\n\t\t<?cs \/if ?>\r\n              <?cs \/if ?>\r\n\t  <\/h3><\/div>\r\n\t  <div class=\"hd3 <?cs var:question.show ?>\">\r\n  \t    <?cs each:contents = question.contents ?>\r\n  \t    <?cs call:drawTags(contents)?>\r\n\t    <?cs \/each ?>\r\n\t    <div class=\"hd4\">\r\n\t      <ol>\r\n\t\t<?cs each: candidate = question.s ?>\r\n                  <?cs if:candidate ?>\r\n\t\t    <?cs if: A.mongo.exam.done && question.correct == name(candidate) ?>\r\n  \t\t      <li class=\"correct\">\r\n  \t\t    <?cs else ?>\r\n\t\t      <li>\r\n\t\t    <?cs \/if ?>\r\n\t\t    <?cs if: question.checked == name(candidate) ?>\r\n                      <input type=\"radio\" id=\"q<?cs name:question ?>a<?cs name:candidate ?>\" name=\"q<?cs name:question ?>a\" value=\"<?cs name:candidate ?>\" checked><\/input>\r\n\t\t    <?cs else ?>\r\n                      <input type=\"radio\" id=\"q<?cs name:question ?>a<?cs name:candidate ?>\" name=\"q<?cs name:question ?>a\" value=\"<?cs name:candidate ?>\"><\/input>\r\n                    <?cs \/if ?>\r\n\t\t    <label for=\"q<?cs name:question ?>a<?cs name:candidate ?>\"><?cs var:candidate ?><\/label>\r\n  \t\t    <\/li>\r\n                  <?cs \/if ?>\r\n\t\t<?cs \/each ?>\r\n\t      <\/ol>\r\n\t    <\/div>\r\n            <?cs if: A.mongo.exam.done ?>\r\n            <div class=\"h4 exp\"><h4>\u89e3\u8aac<\/h4><\/div>\r\n\t    <div class=\"hd4 exp\">\r\n              <?cs each:contents = question.explanation ?>\r\n\t      <?cs call:drawTags(contents)?>\r\n\t      <?cs \/each ?>\r\n\t    <\/div>\r\n            <?cs \/if ?>\r\n            <?cs if: ! A.mongo.exam.done ?>\r\n            <br>\r\n            <?cs if: question.last ?>\r\n            <button type=\"submit\" name=\"op\" value=\"eval\">\u63a1\u70b9<\/buton>\r\n            <?cs else ?>\r\n            <button class=\"gonext\" type=\"button\">\u6b21\u3078<\/button>\r\n            <?cs \/if ?>\r\n            <?cs \/if ?>\r\n\t  <\/div>\r\n\t  <?cs \/each ?>\r\n\t<\/form>\r\n      <\/div><!-- hd2 -->\r\n      <?cs if:A.mongo.exam.writable ?>\r\n      <div class=\"edit\"><a href=\"\/mongo\/exams\/edit\/<?cs var:A.mongo.exam.docid ?>\">\u7de8\u96c6<\/a><\/div>\r\n      <?cs \/if ?>\r\n    <\/div><!-- hd1 -->\r\n  <\/div>\r\n<\/div>\r\n",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"exams\/exampage"
}