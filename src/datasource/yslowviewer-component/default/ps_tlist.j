{
"@R":"1333424730",
"type":"HorizontalWidget",
"subject":"ps_tlist",
"description":"ps_tlist",
"css":"",
"js":"",
"id":"ps_tlist",
"class":"",
"body":"<h1>Test logs<\/h1>\n<style>\na.t<?cs var:A.yslowviewer._t?> {\n  background-color: #CCF8D0;\n}\n<\/style>\n<ul>\n<?cs each:item = A.yslowviewer.times ?>\n<li><a class=\"t<?cs name:item?>\" href=\"ps_url?u=<?cs var:A.yslowviewer.u?>&t=<?cs name:item ?>\"><?cs var:item ?><\/a><\/li>\n<?cs \/each ?>\n<\/ul>",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/PagespeedAction?keys"
],
"_u":"ps_tlist"
}