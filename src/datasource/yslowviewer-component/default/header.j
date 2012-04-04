{
"@R":"1333515605",
"type":"HorizontalWidget",
"subject":"header",
"description":"header",
"css":"#header ul {\n  list-style-type:none;\n}\n#header ul > li {\n  float:left;\n  margin-left:20px;\n  margin-bottom:5px;\n}\n\n#header ul > li > a {\n  cursor: normal;\n  color: #000000;\n}\n#header ul > li > a[href] {\n  cursor: pointer;\n  color: #0000FF;\n}\n\n#header hr {\n  clear:both;\n}\n",
"js":"",
"id":"header",
"class":"",
"body":"<ul>\n <li><a href=\"main\">[Main]<\/a><\/li>\n <li><a <?cs if:?A.yslowviewer.url?>href=\"url?u=<?cs var:A.yslowviewer.url ?>\"<?cs \/if?>>[Results]<\/a><\/li>\n <li><a <?cs if:?A.yslowviewer.url?>href=\"show?u=<?cs var:A.yslowviewer.url ?>\"<?cs \/if?>>[Graph]<\/a><\/li>\n <li><a href=\"ne_main\">[HAR Main]<\/a><\/li>\n <li><a <?cs if:?A.yslowviewer.url?>href=\"ne_url?u=<?cs var:A.yslowviewer.url ?>\"<?cs \/if?>>[HAR view]<\/a><\/li>\n <li><a href=\"ps_main\">[PS Main]<\/a><\/li>\n <li><a <?cs if:?A.yslowviewer.url?>href=\"ps_url?u=<?cs var:A.yslowviewer.url ?>\"<?cs \/if?>>[PS results]<\/a><\/li>\n <li><a <?cs if:?A.yslowviewer.url?>href=\"ps_show?u=<?cs var:A.yslowviewer.url ?>\"<?cs \/if?>>[PS graph]<\/a><\/li>\n<\/ul>\n<hr>\n",
"action":[
""
],
"_u":"header"
}