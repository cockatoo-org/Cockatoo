{
"@R":"1334022275",
"type":"HorizontalWidget",
"subject":"login",
"description":"login",
"css":"\n#login {\n  border: 1px solid #8080F0;\n  color: #888888;\n  text-align:center;\n}\n#login div.input {\n    text-align: left;\n}\n#login div.input > h6 {\n    color: #888888;\n    margin:0;\n    padding:0;\n    text-align: left;\n    width: 80px;\n}\n#login  div.user {\n\/*   float: right;  *\/\n}\n#login  div.user > span {\n  color: blue;\n}\n\n#login div.window {\n  border : 1px solid #C0C8C2;\n  width: 198px;\n  margin: 0 auto;\n}\n\n#login h5 {\n  margin: 0 0 0 0;\n  padding: 0 2px 0 2px;\n  font-size: 1.2em;\n}\n#login div.input > input[type=\"text\"],\n#login div.input > input[type=\"password\"] {\n    margin: 0 0 0 0;\n    padding: 0 0 0 0;\n    position: relative;\n    text-align: left;\n    width: 180px;\n }\n\n",
"js":"",
"id":"login",
"class":"",
"body":"<?cs if: S.login.user ?>\n <div class=\"user\">\n   Welcome <span><?cs var:S.login.user ?><\/span>\n <form method=\"POST\" action=\"\/wiki\/profile\">\n   <input name=\"submit\" type=\"submit\" value=\"logout\" \/>\n   <input name=\"submit\" type=\"submit\" value=\"profile\" \/>\n <\/form>\n <\/div>\n<?cs else ?>\n <div class=\"window\">\n  <form method=\"POST\" action=\"\/wiki\/login\">\n   <div class=\"input\"><h6>User<\/h6> <input name=\"user\" type=\"text\" value=\"\" \/><\/div>\n   <div class=\"input\"><h6>Password<\/h6> <input name=\"passwd\" type=\"password\" value=\"\" \/><\/div>\n   <div class=\"input\"> <input name=\"submit\" type=\"submit\" value=\"login\" \/><input name=\"submit\" type=\"submit\" value=\"password reset\" \/><\/div>\n  <\/form>\n <\/div>\n<?cs \/if ?>\n\n",
"action":[
""
],
"_u":"login"
}