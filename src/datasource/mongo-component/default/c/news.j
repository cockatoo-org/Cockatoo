{
"@R":"1364809461",
"type":"HorizontalWidget",
"subject":"news",
"description":"",
"css":"#cnews ul {\r\n list-style-type: none; \r\n padding : 0;\r\n margin: 0;\r\n}\r\n#cnews ul > li {\r\n margin: 5px 0px 5px 10px;\r\n}\r\n",
"js":"",
"id":"cnews",
"class":"cbox news",
"body":"<div class=\"box\">\r\n<h6><a href=\"<?cs var:C._base ?>\/news\">\u30cb\u30e5\u30fc\u30b9<\/a><\/h6>\r\n<ul>\r\n<?cs each: item=A.mongo.newss?>\r\n  <?cs if: item.public ?>\r\n  <li><a href=\"<?cs var:C._base ?>\/news\/<?cs var: item._u ?>\"><?cs var: item.title ?><\/a><\/li>\r\n  <?cs \/if ?>\r\n<?cs \/each ?>\r\n<\/ul>\r\n<\/div>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/NewsAction?getA"
],
"header":"",
"bottom":"",
"_u":"c\/news"
}