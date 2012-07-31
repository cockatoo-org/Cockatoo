{
"@R":"1343723763",
"type":"HorizontalWidget",
"subject":"graph",
"description":"graph",
"css":"div.graph {\n\/*  margin: 50px; *\/\n  height: 300px;\n  width: 650px;\n}\n#linktext {\n  width: 600px;\n}\n\n\n",
"js":"$(function () {\n  data['summary'][1].yaxis=2;\n  summary = new plot(data['times'],data['summary'],'summary',scores);\n  summary.draw();\n  scores  = new plot(data['times'],data['scores'],'scores',summary);\n  scores.draw();\n  summary.setLink(scores);\n  scores.setLink(summary);\n});\n",
"id":"",
"class":"",
"body":"<h1>summary<\/h1>\n<div id=\"summary\" class=\"graph\"><\/div>\n<h1>scores<\/h1>\n<div id=\"scores\" class=\"graph\"><\/div>\n<script>\n  var data = <?cs var:A.yslowviewer.@json ?>;\n<\/script>\n\n<h1>This page<\/h1>\n<input type=\"text\" id=\"linktext\"><\/input><br>\n<a href=\"\" id=\"linka\">link<\/a>\n",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/YslowAction?getA"
],
"_u":"graph",
"header":"<link rel=\"stylesheet\" href=\"\/_s_\/yslowviewer\/default\/jquery.graph\/graph.css\" type=\"text\/css\" media=\"all\" \/>",
"bottom":"<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/js\/flot\/jquery.flot.js\"><\/script>\n<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/js\/flot\/jquery.flot.selection.js\"><\/script>\n<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/jquery.graph\/graph.js\"><\/script>"
}