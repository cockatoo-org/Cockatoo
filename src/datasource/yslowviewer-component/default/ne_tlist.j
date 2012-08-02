{
"@R":"1343871907",
"type":"HorizontalWidget",
"subject":"ne_tlist",
"description":"ne_tlist",
"css":"#tlist ul {\r\n list-style-type: none;\r\n margin: 5px;\r\n padding: 0 0 0 10px;\r\n}\r\n#tlist ul > li {\r\n \r\n}",
"js":"",
"id":"tlist",
"class":"",
"body":"<style>\r\na.t<?cs var:A.yslowviewer._t?> {\r\n  background-color: #CCF8D0;\r\n}\r\n<\/style>\r\n<ul>\r\n<?cs each:item = A.yslowviewer.times ?>\r\n<li><a class=\"t<?cs name:item?>\" href=\"ne_url?u=<?cs var:A.yslowviewer.u?>&t=<?cs name:item ?>&date=<?cs var:A.yslowviewer.date ?>\"><?cs var:item ?><\/a><\/li>\r\n<?cs \/each ?>\r\n<\/ul>",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/NetexportAction?keys"
],
"_u":"ne_tlist",
"header":"",
"bottom":""
}