<?php
/**
 * cms_page.php - CMS
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
#devices div.value[name=css] { 
  display: none;
}
#devices div.value[name=js] { 
  display: none;
}
#devices div.value[name=device] { 
  display: none;
}

form.Services div.label { 
  width: 50px;
}
form.Services div.value > input { 
  width: 400px;
}
form.Devices div.value > input { 
  width: 400px;
}
form.Devices div.value > textarea { 
  width: 400px;
  height: 20em;
}

form.Paths div.value > input { 
  width: 400px;
}
form.Paths div.value > textarea { 
  width: 400px;
  height: 20em;
}

form.Files div.value > input { 
  width: 400px;
}
form.Patterns div.value > input { 
  width: 400px;
}
form.Patterns div.value > textarea { 
  width: 400px;
  height: 5em;
}
form.Actions div.value > input { 
  width: 400px;
}
form.Actions div.value > textarea { 
  width: 400px;
  height: 15em;
}
/* a { */
/*   width: 180px; */
/*   margin-left: 10px; */
/*   white-space: nowrap; */
/* } */

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
    // add :    { url : 'cms_ajax.php', args : { op : 'addS'}},
    // del :    { url : 'cms_ajax.php', args : { op : 'delG'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    // update : { url : 'cms_ajax.php', args : { op : 'setS'}, hook: function (t) { if (!getVal(t.data,t.index)) return 'Please select service !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getS'}, col : 'name' },
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
    change : function (data) { device.settings.args.sid=data.sid;device.list();},
    reset  : function () { device.reset();}
  });
  service.list();


  var device = $('#devices').cockatoo_list({ 
    title:'Devices', 
    add :    { url : 'cms_ajax.php', args : { op : 'addD'}, hook: function (t) { if ('sid' in t.settings.args) return false; return 'Please select service !'; } },
/*     del :    { url : 'cms_ajax.php', args : { op : 'delD'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select device !'; } }, */
    update : { url : 'cms_ajax.php', args : { op : 'setD'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select device !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getD'}, col : 'name' },
    view : true,
    width: 700,
    dialog : { width: 600 , height: 630 , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      var $sname = root.find('input[name="name"]');
	$sname.attr("readonly","readonly");
      if ( $kind == 'update' || $kind == 'del') {
        var $select = root.find('input[name="device"]').attr("readonly","readonly");
      }
      if ( $kind == 'update' || $kind == 'del') {
	  $select.attr("disabled","disabled");
      }
      var $expires = root.find('select[name="expires"]');
	$expires.change( function (){
            if ( $(this).val() === 'true' ) {
		$(this).parents('form').find('input[name="expires_time"]').removeAttr('disabled');
	    }else{
		$(this).parents('form').find('input[name="expires_time"]').attr('disabled','disabled');
	    }
        });
    }},
    form : {
      rev        : { label: '' , type : 'hidden' },
      did        : { label: '' , type : 'hidden' },
      name       : { label: 'Device' , type : 'text'},
/*    device     : { label: 'device type' , type : 'select', options : { default :'default', iPhone :'iphone', iPad :'ipad', Android :'android', Xperia :'xperia', GALAPAGOS :'galapagos', au : 'au' , softbank : 'softbank' , docomo : 'docomo' } , def : 'default'},  */
      device     : { label: 'device type' , type : 'text', def : 'default'},
      eredirect  : { label: 'error redirect' , type : 'text'},
      layout     : { label: 'Layout editor' , type : 'html'},
      expires      : { label: 'expires header' , type : 'select', options : { enable :true, disable :false} , def : false},
      expires_time : { label: 'expires seconds' , type : 'text' , def : 0},
      css        : { label: 'CSS' , type : 'textarea' },
      js         : { label: 'JS' , type : 'textarea' },
      header     : { label: 'Header' , type : 'textarea' , def : "\n" + 
            '<meta http-equiv="content-type" content="text/html; charset=utf-8">' + "\n" + 
            '<meta http-equiv="content-style-type" content="text/css">' + "\n" + 
            '<meta http-equiv="content-script-type" content="text/javascript">' + "\n"
            },
      pheader   : { label: 'Protocol header' , type : 'textarea' , def : ""},
      bottom    : { label: 'Body bottom' , type : 'textarea' , def : ""}
    },
    validator: {
      rules: {
	name: {
          required: true
        },
	expires_time: {
	  number: true
	}
      }
    },
    change : function (data) { path.settings.args.sid=data.sid; path.settings.args.did=data.did; path.list();},
    reset  : function () { path.reset();}
  });

  var path = $('#paths').cockatoo_list({ 
    title:'Paths', 
    add :    { url : 'cms_ajax.php', args : { op : 'addP'}, hook: function (t) { if ('sid' in t.settings.args) return false; return 'Please select service !'; } },
    /* del :    { url : 'cms_ajax.php', args : { op : 'delP'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select path !'; } }, */
    update : { url : 'cms_ajax.php', args : { op : 'setP'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select path !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getP'}, col : 'name' },
    view : true,
    width: 700,
    dialog : { width: 600 , height: 500 , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      root.find('input[name="page"]').attr("readonly","readonly");
      root.find('input[name="layout"]').attr("readonly","readonly");
      //root.find('textarea[name="contents"]').attr("readonly","readonly");
      root.find('textarea[name="contents"]').attr("disabled","disabled");
      if ( $kind == 'update') {
	var $sname = root.find('input[name="name"]');
	  $sname.attr("readonly","readonly");
      }
      var $session = root.find('select[name="session"]');
	$session.change( function (){
            if ( $(this).val() === 'true' ) {
		$(this).parents('form').find('input[name="session_exp"]').removeAttr('disabled');
	    }else{
		$(this).parents('form').find('input[name="session_exp"]').attr('disabled','disabled');
	    }
        });
      var $expires = root.find('select[name="expires"]');
	$expires.change( function (){
            if ( $(this).val() === 'true' ) {
		$(this).parents('form').find('input[name="expires_time"]').removeAttr('disabled');
	    }else{
		$(this).parents('form').find('input[name="expires_time"]').attr('disabled','disabled');
	    }
        });
      var $ctype = root.find('select[name="_ctype"]');
	$ctype.change( function(){
	    $('input[name="ctype"]').val($(this).val());
	  if ( $(this).val() === 'plain' ) {
	    $('textarea[name="header"]').attr('disabled','disabled');
	  }else if ( $(this).val() === 'json' ) {
	    $('textarea[name="header"]').attr('disabled','disabled');
	  }else if ( $(this).val() === 'binary' ) {
	    $('textarea[name="header"]').attr('disabled','disabled');
	  }else if ( $(this).val() === 'html' ) {
	    $('textarea[name="header"]').removeAttr('disabled');
	  }
	  $(':input').show();
	  $(':input[disabled]').hide();
	});
	$ctype.val($('input[name="ctype"]').val());
	$ctype.change();
	$ctype.attr('disabled','disabled');
    }},
    form : {
      rev   : { label: '' , type : 'hidden' },
      pid   : { label: '' , type : 'hidden' },
      name  : { label: 'Path' , type : 'text'},
      _ctype     : { label: 'Type' , type : 'select', options : { html :'html', plain :'plain' , json : 'json' , binary : 'binary'} , def : 'html'},
      ctype      : { label: '' , type : 'hidden' },
      redirect   : { label: 'force redirect' , type : 'text'},
      eredirect  : { label: 'error redirect' , type : 'text'},
      page   : { label: 'Real page' , type : 'html'},
      layout   : { label: 'Layout editor' , type : 'html'},
      pre_action  : { label: 'first action', type : 'text'},
      post_action : { label: 'last action' , type : 'text'},
      session     : { label: 'session object' , type : 'select', options : { enable :true, disable :false} , def : true},
      session_exp : { label: 'session cookie expire' , type : 'text' , def : 0},
      expires      : { label: 'expires header' , type : 'select', options : { enable :true, disable :false} , def : false},
      expires_time : { label: 'expires seconds' , type : 'text' , def : 0},
      header   : { label: 'Header' , type : 'textarea' , def : "\n" + 
            '<meta name="description" content="">' + "\n" 
            },
      pheader   : { label: 'Protocol header' , type : 'textarea' , def : ""},
      bottom    : { label: 'Body bottom' , type : 'textarea' , def : ""},
      contents   : { label: 'Containing' , type : 'textarea'}
    },
    validator: {
      rules: {
	name: {
	  required: true
	},
	pre_action: {
	  brl: true
	},
	post_action: {
	  brl: true
	},
	session_exp: {
	  number: true
	},
	expires_time: {
	  number: true
	}
      }
      /* messages: { */
      /* } */
    },
    change : function (data) { 
      var t = path;
      var args = {};
      args.sid = data.sid;
      args.did = data.did;
      args.pid = data.pid;
      args.op = 'getPP';
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
     <div id="devices" class="main-left" ></div>
    </div>
    <div class="main-block">
     <div id="paths" class="main-left" ></div>
    </div>
   </div>
  </div>
 </body>
</html>
