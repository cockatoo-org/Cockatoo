<?php
/**
 * cms_static.php - CMS
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
<link rel="stylesheet" href="css/redmond/jquery-ui-1.8.22.custom.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/cockatoo-cms.css" type="text/css" media="all" />
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
#static_contents div.value[name=css] { 
  display: none;
}
#static_contents div.value[name=js] { 
  display: none;
}

form.Services div.label { 
  width: 50px;
}
form.Services div.value > input { 
  width: 600px;
}
form.Static_Contents div.value > input { 
  width: 600px;
}
form.Static_Contents div.value > textarea { 
  width: 600px;
  height: 20em;
}


</style>

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-json/jquery.json-2.2.js"></script>
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
    //add :    { url : 'cms_ajax.php', args : { op : 'addS'}},
    //del :    { url : 'cms_ajax.php', args : { op : 'delG'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    //update : { url : 'cms_ajax.php', args : { op : 'setS'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getS'}, col : 'name' },
    view : true,
    width: 150,
    dialog : { width: 500 , height: 150  , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      if ( $kind == 'update') {
	var $sname = root.find('input[name="name"]');
	  $sname.attr("readonly","readonly");
      }
    }},
    form : {
      rev   : { label: '' , type : 'hidden' },
      service_id   : { label: '' , type : 'hidden' },
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
    change : function (data) { static_content.settings.args.service_id=data.service_id;static_content.list();},
    reset  : function () { static_content.reset();}
  });
  service.list();


  var static_content = $('#static_contents').cockatoo_list({ 
    title:'Static_Contents', 
    add :    { url : 'cms_ajax.php', args : { op : 'addSC'}, hook: function (t) { if ('service_id' in t.settings.args) return false; return 'Please select service !'; } },
    del :    { url : 'cms_ajax.php', args : { op : 'delSC'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select content !'; } },
    update : { url : 'cms_ajax.php', args : { op : 'setSC'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select content !'; } },
        // copy   : { url : 'cms_ajax.php', args : { op : 'cpSC'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select content !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getSC'}, col : 'brl' },
    view : true,
    width: 700,
    dialog : { width: 800 , height: 630 , post_init: function ( root ) { 
      root.find('textarea[name="required"]').attr("disabled","disabled");
      var $kind = root.attr('kind');
      root.find('input[name="brl"]').attr("readonly","readonly");
      root.find('input[name="etag"]').attr("readonly","readonly");
      if ( $kind == 'update' || $kind == 'del') {
        root.find('input[name="name"]').attr("readonly","readonly");
        root.find('select').attr("readonly","readonly");
      }
    }},
    form : {
      rev        : { label: '' , type : 'hidden' },
      static_id  : { label: '' , type : 'hidden' },
      brl        : { label: 'Brl' , type : 'text'},
      name       : { label: 'Name' , type : 'text'},
      type       : { label: 'Content-type' , type : 'text'},
      etag       : { label: 'Etag' , type : 'text'},
      expires    : { label: 'Expires' , type : 'text'},
      bin        : { label: '' , type : 'hidden'},
      body       : { label: 'Data body' , type : 'textarea'},
      description: { label: 'Description' , type : 'textarea'}
    },
    validator: {
      rules: {
	name: {
          required : true
        },
	expires: {
	  number: true
	}
      }
    },
    change : function (data) { 
      var t = static_content;
      var args = {};
      args.service_id = data.service_id;
      args.static_id = data.static_id;
      args.op = 'getSCC';
	$.ajax({
	  url: t.settings.list.url,
	  type: 'POST',
	  dataType: 'json',
	  data: args,
	  t: t,
	  i: t.index,
	  success: function (data){
	    if ( 'emsg' in data ) {
	      this.t.reset();
	      var m = t.root.find('b.message');
	      m.text(data.emsg).slideDown(1000);
	      setTimeout(function(){ m.slideUp(1000);},3000);
	      return;
	    }
	    $.extend( this.t.data[this.t.index], data);
	    this.t.view();
	  }
	});
    },
    reset  : function () { }
  });
});
// -->
</script>
</head>
 <body>
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
     <div id="static_contents" class="main-left" ></div>
    </div>
   </div>
  </div>
 </body>
</html>
