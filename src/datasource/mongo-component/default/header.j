{
"@R":"1369100202",
"type":"HorizontalWidget",
"subject":"header",
"description":"header",
"css":"#header div.nav.top {\r
  background-color: #402817;\r
  color: #AA814D;\r
  width:100%;\r
  height: 93px;\r
  display:block;\r
  float:left;\r
}\r
#header div.nav.top img {\r
  height: 93px;\r
}\r
#header #logo {\r
  float:left;\r
}\r
#header #title {\r
  font-size: 2em;\r
  margin: 30px;\r
  color: #f0f0f0;\r
  float:left;\r
}\r
#header #identity {\r
  min-width:100px;\r
  text-align: center;\r
  margin: 2px 8px;\r
  float:right;\r
}\r
#header #identity a {\r
  color: #AA814D;\r
  cursor: pointer;\r
}\r
#header #identity > div{\r
  margin: 5px 0 0 0;\r
  padding: 3px;\r
  border-style: solid;\r
  border-width: 1px;\r
  background-color: #cccccc;\r
  opacity: 0.5;\r
  display: none;\r
}\r
#header #identity:hover > div{\r
  display: block;\r
}\r
#header #identity > div > form {\r
  display:none;\r
}\r
\r
#header div.nav.main {\r
  line-height: 1.31;\r
  background-color: #f6f4cd;\r
  border-top: 1px solid #f6f4cd;\r
  color: #4c3a2c;\r
  width:100%;\r
  float:right;\r
}\r
#header div.nav.main ul {\r
  list-style: none;\r
  padding: 0;\r
  margin: 0 5px;\r
}\r
/*\r
#header div.nav.main ul.left > li {\r
  float: left;\r
}\r
#header div.nav.main ul > li.emp {\r
  background-color: #f05500;\r
}\r
#header div.nav.main ul > li.emp:hover a {\r
  color: #4c3a2c;\r
}\r
#header div.nav.main ul > li.emp a {\r
  color: #ffffff;\r
}\r
*/\r
#header div.nav.main ul > li {\r
  font-size: 0.9em;\r
  float: right;\r
  padding: 2px 6px;\r
  font-weight:600;\r
}\r
\r
#header div.nav.main ul > li.selected {\r
  background-color: #F0F0F0;\r
}\r
#header div.nav.main ul > li:hover {\r
  background-color: #f8f8f8;\r
}\r
#header div.nav.main a {\r
  color: #4c3a2c;\r
}\r
",
"js":"$( function (){\r
  mainNavs = $('#header div.nav.main > ul > li');\r
  mainNavs.each( function () {\r
    $(this).removeClass('selected');\r
    link = $(this).find('> a').attr('href');\r
    if ( link == window.location.pathname) {\r
      $(this).addClass('selected');\r
    }\r
  });\r
  $('#header #identity div.logout a.logout').click(function(ev){\r
    $(this).next('form').find('input[type=\"submit\"]').click();\r
  });\r
})\r
",
"id":"header",
"class":"",
"body":"<nav><div class=\"nav top\" role=\"navigation\">\r
  <div id=\"logo\"><img alt=\"MongoDB JP User Group \" src=\"/_s_/mongo/default/img/mongojp-logo.png\"></img><img alt=\"\u3086\u308b\u30ad\u30e3\u30e9\" src=\"/_s_/mongo/default/img/mongo-squid-mini.png\"></img></div>\r
  <div id=\"title\">\u65e5\u672cMongoDB\u30e6\u30fc\u30b6\u30fc\u4f1a</div>\r
<?cs if:! S.login.user ?>\r
  <div id=\"identity\"><a href=\"<?cs var:C._base ?>/login?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs /if ?>\">login</a></div>\r
<?cs else ?>\r
  <div id=\"identity\"><a user=\"<?cs var:S.login.user ?>\"><?cs var:S.login.name ?></a>\r
   <div class=\"logout\">\r
    <a class=\"logout\">logout</a>\r
    <form method=\"post\" action=\"<?cs var:C._base ?>/profile\">\r
     <input type=\"submit\" name=\"submit\" value=\"logout\" />\r
    </form>\r
   </div>\r
   <?cs if: S.login.root ?>\r
   <div class=\"admin\">\r
    <a href=\"<?cs var:C._base ?>/admin\">admin tool</a>\r
   </div>\r
   <?cs else ?>\r
   <div class=\"profile\">\r
    <a href=\"<?cs var:C._base ?>/profile\">edit profile</a>\r
   </div>\r
   <?cs /if ?>\r
  </div>\r
<?cs /if ?>\r
</div></nav>\r
<nav><div class=\"nav main\" role=\"navigation\">\r
<!--\r
  <ul class=\"left\"> \r
    <li class=\"emp\"><a href=\"<?cs var:C._base ?>/noryo2013\">\u7d0d\u6dbcMongo\u796d\u308a2013</a></li>\r
  </ul>\r
-->\r
  <ul>\r
    <li><a href=\"<?cs var:C._base ?>/licence\">\u30e9\u30a4\u30bb\u30f3\u30b9</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/links\">\u30ea\u30f3\u30af</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/news\">\u30cb\u30e5\u30fc\u30b9</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/events\">\u30a4\u30d9\u30f3\u30c8</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/exams\">\u554f\u984c\u96c6</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/tips\">TIPS</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/docs\">\u30c9\u30ad\u30e5\u30e1\u30f3\u30c8</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/main\">\u30e1\u30a4\u30f3</a></li>\r
  </ul>\r
</div></nav>\r
<br clear=\"both\" />\r
",
"action":[
""
],
"_u":"header",
"header":"",
"bottom":""
}