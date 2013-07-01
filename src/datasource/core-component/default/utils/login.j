{
"@R":"1372663095",
"type":"HorizontalWidget",
"subject":"login",
"description":"login",
"css":"#login form {\r
  display:inline;\r
}\r
#login {\r
  text-align:center;\r
}\r
#login div.input {\r
    text-align: left;\r
}\r
#login div.input > h6 {\r
    margin:0;\r
    padding:0;\r
    text-align: left;\r
    width: 80px;\r
}\r
#login  div.user {\r
/*   float: right;  */\r
}\r
#login  div.user > span {\r
/*  color: blue; */\r
}\r
\r
#login h5 {\r
  margin: 0 0 0 0;\r
  padding: 0 2px 0 2px;\r
  font-size: 1.2em;\r
}\r
#login div.input > input[type=\"text\"],\r
#login div.input > input[type=\"password\"] {\r
    margin: 0 0 0 0;\r
    padding: 0 0 0 0;\r
    position: relative;\r
    text-align: left;\r
    width: 180px;\r
 }\r
\r
\r
#login div.oauth div {\r
  border-radius: 4px;\r
  border-style: solid;\r
  border-width: 1px;\r
//  border-color: #2020cc;\r
  margin: 5px auto;\r
  padding: 5px;\r
  width: 300px;\r
}\r
#login div.oauth div a {\r
  font-size: 1.8em;\r
}\r
\r
#login div.oauth img {\r
  height:16px;\r
  width:16px;\r
}\r
#login div.admin {\r
  float: right;\r
  padding: 5px;\r
  width: 200px;\r
}\r
#login div.admin a.admin {\r
  cursor: pointer;\r
}\r
#login div.admin form {\r
  display: none;\r
}\r
",
"js":"$(function(){\r
  $('#login div.admin a.admin').click(function(ev){\r
    $(this).next('form').slideToggle();\r
  });\r
});",
"id":"login",
"class":"",
"body":"<?cs if: S.login.user ?>\r
<div class=\"window\">\r
 <div class=\"user\">\r
   Welcome <span><?cs var:S.login.user ?></span>\r
<?cs if: S.login.root ?>\r
<a id=\"reset\" href=\"<?cs var:C._base ?>/admin\">admin tool</a>\r
<?cs /if ?>\r
 </div>\r
</div>\r
<?cs else ?>\r
<div class=\"window\">\r
  <div class=\"oauth\">\r
  <div class=\"twitter\">\r
  <a href=\"<?cs var:C._base ?>/logintwitter?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs /if ?>\"><img src=\"/_s_/core/default/twitter.gif\" alt=\"twitter oauth\"></img> Twitter\u3067\u30ed\u30b0\u30a4\u30f3</a>\r
  </div>\r
  <div class=\"google\">  \r
  <a href=\"<?cs var:C._base ?>/logingoogle?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs /if ?>\"><img src=\"/_s_/core/default/google.gif\" alt=\"google oauth\"></img> Google\u3067\u30ed\u30b0\u30a4\u30f3</a>\r
  </div>\r
  </div>\r
  <div class=\"admin\">\r
  <a class=\"admin\">\u7ba1\u7406\u8005\u30c4\u30fc\u30eb</a>\r
  <form method=\"POST\" action=\"<?cs var:C._base ?>/profile?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs /if ?>\">\r
   <div class=\"input\"><h6>User</h6> <input name=\"user\" type=\"text\" value=\"\" /></div>\r
   <div class=\"input\"><h6>Password</h6> <input name=\"passwd\" type=\"password\" value=\"\" /></div>\r
   <?cs if:?S._g.r ?>\r
   <div class=\"input\"><input name=\"r\" type=\"hidden\" value=\"<?cs var:S._g.r ?>\" /></div>\r
   <?cs /if ?> \r
   <div class=\"input\"> <input name=\"submit\" type=\"submit\" value=\"login\" /><input name=\"submit\" type=\"submit\" value=\"password reset\" /></div>\r
  </form>\r
  </div>\r
</div>\r
<?cs /if ?>\r
\r
",
"action":[
""
],
"_u":"utils/login",
"header":"",
"bottom":""
}