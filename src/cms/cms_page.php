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
#templates div.value[name=css] { 
  display: none;
}
#templates div.value[name=js] { 
  display: none;
}
#templates div.value[name=template] { 
  display: none;
}

form.Services div.label { 
  width: 50px;
}
form.Services div.value > input { 
  width: 400px;
}
form.Templates div.value > input { 
  width: 400px;
}
form.Templates div.value > textarea { 
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
    change : function (data) { template.settings.args.service_id=data.service_id;template.list();},
    reset  : function () { template.reset();}
  });
  service.list();


  var template = $('#templates').cockatoo_list({ 
    title:'Templates', 
    add :    { url : 'cms_ajax.php', args : { op : 'addD'}, hook: function (t) { if ('service_id' in t.settings.args) return false; return 'Please select service !'; } },
/*     del :    { url : 'cms_ajax.php', args : { op : 'delD'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select template !'; } }, */
    update : { url : 'cms_ajax.php', args : { op : 'setD'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select template !'; } },
    list :   { url : 'cms_ajax.php', args : { op : 'getD'}, col : 'name' },
    view : true,
    width: 700,
    dialog : { width: 600 , height: 630 , post_init: function ( root ) { 
      var $kind = root.attr('kind');
      var $sname = root.find('input[name="name"]');
	$sname.attr("readonly","readonly");
      if ( $kind == 'update' || $kind == 'del') {
        var $select = root.find('input[name="template"]').attr("readonly","readonly");
      }
      if ( $kind == 'update' || $kind == 'del') {
	  $select.attr("disabled","disabled");
      }
      var $session = root.find('select[name="session"]');
	$session.change( function (){
          var $session_exp = $(this).parents('form').find('input[name="session_exp"]');
	    $session_exp.removeAttr('readonly');
	    $session_exp.val($(this).val());
          if ( $(this).val() == -1 ) {
	      $session_exp.attr('readonly','readonly');
          }else if ( $(this).val() == 0 ) {
	      $session_exp.attr('readonly','readonly');
	  }
        });
      var $expires = root.find('select[name="expires"]');
	$expires.change( function (){
	  var $expires_time = $(this).parents('form').find('input[name="expires_time"]');
	    $expires_time.removeAttr('readonly');
	    $expires_time.val($(this).val());
          if ( $(this).val() == 0 ) {
	      $expires_time.attr('readonly','readonly');
	  }
        });
    }},
    form : {
      rev        : { label: '' , type : 'hidden' },
      template_id        : { label: '' , type : 'hidden' },
      name       : { label: 'Template' , type : 'text'},
/*    template     : { label: 'template type' , type : 'select', options : { default :'default', iPhone :'iphone', iPad :'ipad', Android :'android', Xperia :'xperia', GALAPAGOS :'galapagos', au : 'au' , softbank : 'softbank' , docomo : 'docomo' } , def : 'default'},  */
      template     : { label: 'template type' , type : 'text', def : 'default'},
      redirect   : { label: 'top page (301)' , type : 'text'},
      eredirect  : { label: 'error page (302)' , type : 'text'},
      layout     : { label: 'Layout editor' , type : 'html'},
      session     : { label: 'default session object' , type : 'select', options : { use :1 , temporary: 0 , disable :-1} , def : 0},
      session_exp : { label: 'default session expire' , type : 'text' , def : 0},
      expires      : { label: 'expires header' , type : 'select', options : { enable :1, disable :0} , def : 0},
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
	session_exp: {
	  number: true
	},
	expires_time: {
	  number: true
	}
      }
    },
    change : function (data) { path.settings.args.service_id=data.service_id; path.settings.args.template_id=data.template_id; path.list();},
    reset  : function () { path.reset();}
  });

  var path = $('#paths').cockatoo_list({ 
    title:'Paths', 
    add :    { url : 'cms_ajax.php', args : { op : 'addP'}, hook: function (t) { if ('service_id' in t.settings.args) return false; return 'Please select service !'; } },
    del :    { url : 'cms_ajax.php', args : { op : 'delP'}, hook: function (t) { if (getVal(t.data,t.index)){return false;} return 'Please select path !'; } },
    update : { url : 'cms_ajax.php', args : { op : 'setP'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select path !'; } },
    copy   : { url : 'cms_ajax.php', args : { op : 'cpP'}, hook: function (t) { if (getVal(t.data,t.index)) return false; return 'Please select path !'; } },
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
          var $session_exp = $(this).parents('form').find('input[name="session_exp"]');
	    $session_exp.removeAttr('readonly');
	    $session_exp.val($(this).val());
          if ( $(this).val() == -1 ) {
	      $session_exp.attr('readonly','readonly');
          }else if ( $(this).val() == 0 ) {
	      $session_exp.attr('readonly','readonly');
          } else if ( $(this).val() == 0x7fffffff ) {
	      $session_exp.attr('readonly','readonly');
	  }
        });
      var $expires = root.find('select[name="expires"]');
	$expires.change( function (){
	  var $expires_time = $(this).parents('form').find('input[name="expires_time"]');
	    $expires_time.removeAttr('readonly');
	    $expires_time.val($(this).val());
          if ( $(this).val() == 0 ) {
	      $expires_time.attr('readonly','readonly');
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
      page_id   : { label: '' , type : 'hidden' },
      name  : { label: 'Path' , type : 'text'},
      _ctype     : { label: 'Type' , type : 'select', options : { html :'html', plain :'plain' , json : 'json' , binary : 'binary'} , def : 'html'},
      ctype      : { label: '' , type : 'hidden' },
      redirect   : { label: 'redirect (301)' , type : 'text'},
      eredirect  : { label: 'error page (302)' , type : 'text'},
      page   : { label: 'Real page' , type : 'html'},
      layout   : { label: 'Layout editor' , type : 'html'},
      pre_action  : { label: 'pre  action', type : 'text'},
      post_action : { label: 'post action' , type : 'text'},
      session     : { label: 'session object' , type : 'select', options : { use :1 , default:0x7fffffff , temporary: 0 , disable :-1} , def : 0x7fffffff},
      session_exp : { label: 'session expire' , type : 'text' , def : 0x7fffffff},
      expires      : { label: 'expires header' , type : 'select', options : { enable :1, disable :0} , def : 0},
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
      args.service_id = data.service_id;
      args.template_id = data.template_id;
      args.page_id = data.page_id;
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
<?php
  namespace Cockatoo;
  require_once(Config::COCKATOO_ROOT.'/wwwutils/core/cms_link.php');
?>
  <div id="main">
   <div id="services" class="main-left"></div>
   <div class="main-left" >
    <div class="main-block">
     <div id="templates" class="main-left" ></div>
    </div>
    <div class="main-block">
     <div id="paths" class="main-left" ></div>
    </div>
   </div>
  </div>
 </body>
</html>
