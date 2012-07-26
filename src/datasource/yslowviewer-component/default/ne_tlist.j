{
"@R":"1343112814",
"type":"HorizontalWidget",
"subject":"ne_tlist",
"description":"ne_tlist",
"css":"",
"js":"",
"id":"tlist",
"class":"",
"body":"<h1>Test logs<\/h1>\n<style>\na.t<?cs var:A.yslowviewer._t?> {\n  background-color: #CCF8D0;\n}\n<\/style>\n<ul>\n<?cs each:item = A.yslowviewer.times ?>\n<li><a class=\"t<?cs name:item?>\" href=\"ne_url?u=<?cs var:A.yslowviewer.u?>&t=<?cs name:item ?>&date=<?cs var:A.yslowviewer.date ?>\"><?cs var:item ?><\/a><\/li>\n<?cs \/each ?>\n<\/ul>",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/NetexportAction?keys"
],
"_u":"ne_tlist"
}