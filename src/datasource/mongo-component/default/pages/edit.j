{
"@R":"1363596018",
"type":"HorizontalWidget",
"subject":"edit",
"description":"edit",
"css":"#editPage {\r\n  text-align: center;\r\n  padding: 10px;\r\n}\r\n#editPage  textarea[name=\"origin\"] {\r\n  width: 800px;\r\n  height:600px;\r\n}\r\n",
"js":"",
"id":"editPage",
"class":"",
"body":"<form method =\"POST\" action=\"<?cs var:C._base ?>\/edit\/<?cs var: A.mongo.page.title?>\">\r\n  <textarea name=\"origin\"><?cs var:A.mongo.page.origin ?><\/textarea>\r\n  <div>\r\n  <input type=\"submit\" name=\"op\" value=\"preview\"><\/input>\r\n  <input type=\"submit\" name=\"op\" value=\"save\"><\/input>\r\n  <a target=\"_blank\" href=\"<?cs var:C._base ?>\/notation\">notation<\/a>\r\n  <\/div>\r\n<\/form>",
"action":[
"action:\/\/mongo-action\/mongo\/PageAction?get"
],
"_u":"pages\/edit",
"header":"",
"bottom":""
}