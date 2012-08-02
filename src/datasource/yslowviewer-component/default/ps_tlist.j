{
"@R":"1343871953",
"type":"HorizontalWidget",
"subject":"ps_tlist",
"description":"ps_tlist",
"css":"#tlist ul {\r\n list-style-type: none;\r\n margin: 5px;\r\n padding: 0 0 0 10px;\r\n}\r\n#tlist ul > li {\r\n \r\n}",
"js":"",
"id":"ps_tlist",
"class":"",
"body":"<style>\r\na.t<?cs var:A.yslowviewer._t?> {\r\n  background-color: #CCF8D0;\r\n}\r\n<\/style>\r\n<ul>\r\n<?cs each:item = A.yslowviewer.times ?>\r\n<li><a class=\"t<?cs name:item?>\" href=\"ps_url?u=<?cs var:A.yslowviewer.u?>&t=<?cs name:item ?>&date=<?cs var:A.yslowviewer.date ?>\"><?cs var:item ?><\/a><\/li>\r\n<?cs \/each ?>\r\n<\/ul>",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/PagespeedAction?keys"
],
"_u":"ps_tlist",
"header":"",
"bottom":""
}