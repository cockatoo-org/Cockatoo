{
"@R":"1343370952",
"type":"HorizontalWidget",
"subject":"profile",
"description":"profile",
"css":"#profile {\n  border: 1px solid #8080F0;\n  color: #888888;\n  text-align:left;\n  padding: 0 50px;\n}\n\n#profile div.window {\n  border : 1px solid #C0C8C2;\n  width: 200px;\n  margin: 0 0;\n  text-align:left;\n}\n\n#profile h5 {\n  margin: 0 0 0 0;\n  padding: 0 2px 0 2px;\n  font-size: 1.2em;\n}\n\n#profile div.input {\n  border-bottom : 1px solid #F0F0F0;\n  text-align:left;\n}\n\n#profile div.input > h6 {\n   margin: 0 0 0 0;\n   padding: 0 5px 0 0;\n   width: 80px;\n   text-align:left;\n  color: #888888;\n}\n#profile div.input > input[type=\"text\"],\n#profile div.input > input[type=\"password\"] {\n    margin: 0 0 0 0;\n    padding: 0 0 0 0;\n    position: relative;\n    text-align: left;\n    width: 180px;\n }",
"js":"",
"id":"profile",
"class":"",
"body":"<h5>Update profile<\/h5>\n<div class=\"window\">\n<form class=\"setuser\" method=\"POST\" action=\"profile\">\n<div class=\"input\"> <h6>User<\/h6> <input name=\"passwd\" type=\"text\" value=\"<?cs var:S.login.user ?>\" readonly=\"readonly\" \/><\/div>\n<div class=\"input\"> <h6>Password<\/h6> <input name=\"passwd\" type=\"password\" value=\"\" \/><\/div>\n<div class=\"input\"> <h6>Confirm<\/h6> <input name=\"confirm\" type=\"password\" value=\"\" \/><\/div>\n<div class=\"input\"> <h6>Email<\/h6> <input name=\"email\" type=\"text\" value=\"<?cs var:S.login.email ?>\" \/><\/div>\n<div class=\"input\">\n<input name=\"submit\" type=\"submit\" value=\"update profile\" \/>\n<\/div>\n",
"action":[
""
],
"_u":"utils\/profile"
}