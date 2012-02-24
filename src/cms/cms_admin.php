<?php
/**
 * cms_admin.php - CMS
 *  
 * @access public
 * @package cockatoo-cms
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../def.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-script-type" content="text/javascript">
<meta name="description" content="">
<title>Cockatoo - page editor β - 0.0.1 </title>
<link rel="stylesheet" href="js/jquery-ui/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="all" />
<link rel="stylesheet" href="js/cockatoo-cms.css" type="text/css" media="all" />
<style type="text/css">
/* main layout */
body {
  font-size: 11px;
}
td {
  vertical-align: top;
}
/* list/view */
#services div.label { 
  width: 50px;
}

form.Services div.label { 
  width: 50px;
}
form.Services div.value > input { 
  width: 400px;
}
form.Accounts div.value > input { 
  width: 400px;
}
form.Accounts div.value > textarea { 
  width: 400px;
  height: 20em;
}


</style>

<script type="text/javascript" src="js/jquery-1.4.4.js"></script>
<script type="text/javascript" src="js/jquery-json/jquery.json-2.2.js"></script>
<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/cockatoo-cms.js"></script>
<script type="text/javascript" src="js/cockatoo-validator.js"></script>
<script type="text/javascript">
<!--
$(function () {
  function getVal ( hash , index ) {
    if ( hash && index && hash[index] ) 
      return hash[index];
    return null;
  }
  function getVal2 ( hash , index , index2 ) {
    return getVal(getVal(hash,index),index2);
  }
  var service = $('#services').cockatoo_list({ 
    title:'Services', 
    add :    { url : 'cms_ajax_admin.php', args : { op : 'addS'}},
    del :    { url : 'cms_ajax_admin.php', args : { op : 'delS'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    update : { url : 'cms_ajax_admin.php', args : { op : 'setS'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    list :   { url : 'cms_ajax_admin.php', args : { op : 'getS'}, col : 'name' },
    view : true,
    width: 150,
    dialog : { width: 500 , height: 150  , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      root.find('input[name="layout"]').attr("readonly","readonly");
      if ( $kind == 'update') {
	var $sname = root.find('input[name="name"]');
	  $sname.attr("readonly","readonly");
      }
    }},
    form : {
      rev   : { label: '' , type : 'hidden' },
      sid   : { label: '' , type : 'hidden' },
      name  : { label: 'Service' , type : 'text' }
    },
    validator : {
      rules: {
	name: {
	  required: true,
          nospace: true
	}
      }
    },
    change : function (data) { accounts.settings.args.sid=data.sid;accounts.list();},
    reset  : function () { accounts.reset();}
  });
  service.list();


  var accounts = $('#accounts').cockatoo_list({ 
    title:'Account', 
    add :    { url : 'cms_ajax_admin.php', args : { op : 'addA'}, hook: function (t) { if ('sid' in t.settings.args) return false; return 'Please select service !'; } }, 
    del :    { url : 'cms_ajax_admin.php', args : { op : 'delA'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select account !'; } },
    update : { url : 'cms_ajax_admin.php', args : { op : 'setA'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select account !'; } },
    list :   { url : 'cms_ajax_admin.php', args : { op : 'getA'}, col : 'name' },
    view : true,
    width: 700,
    dialog : { width: 600 , height: 200 , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      if ( $kind == 'update' || $kind == 'delete' ) {
	var $sname = root.find('input[name="name"]');
	  $sname.attr("readonly","readonly");
      }
    }},
    form : {
      rev        : { label: '' , type : 'hidden' },
      aid        : { label: '' , type : 'hidden' },
      name       : { label: 'Name' , type : 'text'},
      role       : { label: 'Role' , type : 'select', options : { None:'0',Readable :'1',  Writable: '2', Admin :'8'} , def : '1'}
    },
    validator: {
      rules: {
	name: {
          required: true
        },
      }
    },
    change : function (data) { },
    reset  : function () { }
  });
});
// -->
</script>
</head>
 <body>
  <div id="header">
   <h1>Cockatoo - page editor β</h1>
   <div id="header-main">
<?php
  namespace Cockatoo;
  require_once(Config::COCKATOO_ROOT.'/wwwutils/core/cms_link.php');
?>
   </div>
  </div>
  <div id="main">
   <div id="services" class="main-left" ></div>
   <div class="main-left" >
    <div class="main-block">
     <div id="accounts" class="main-left" ></div>
    </div>
   </div>
  </div>
 </body>
</html>
