{
"@R":"1364880090",
"type":"HorizontalWidget",
"subject":"events",
"description":"",
"css":"#cevents ul {\r\n list-style-type: none; \r\n padding : 0;\r\n margin: 0;\r\n}\r\n#cevents ul > li {\r\n margin: 5px 0px 5px 10px;\r\n}\r\n",
"js":"",
"id":"cevents",
"class":"cbox cevnets",
"body":"<div class=\"box\">\r\n<h6><a href=\"<?cs var:C._base ?>\/events\">\u30a4\u30d9\u30f3\u30c8\u60c5\u5831<\/a><\/h6>\r\n<ul>\r\n<?cs each: item=A.mongo.events ?>\r\n  <?cs if: item.public ?>\r\n  <li><a href=\"<?cs var:C._base ?>\/events\/<?cs var: item._u ?>\"><?cs var: item.title ?><\/a><\/li>\r\n  <?cs \/if ?>\r\n<?cs \/each ?>\r\n<\/ul>\r\n<\/div>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/EventAction?getA&_limit=10"
],
"header":"",
"bottom":"",
"_u":"c\/events"
}