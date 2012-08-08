;(function($) {
    $.extend($.fn,{
      cockatoo_list: function(options){
	if (!this.length) {
	  return;
	}
	return new $.CockatooList($(this),options);
      }
    });

    $.CockatooList = function (node,options) {
      var t = this;
      t.root  = node;
      t.settings = $.extend( {}, $.CockatooList.defaults, options );
      t.init();
    };
    $.extend($.CockatooList, {
      defaults: {
	custom1 : null,
	get : null,
	add : null,
	del : null,
	update: null,
	copy: null,
	title : null,
	view : null,
	args : {},
	change : function () {},
	reset : function () {},
	width : 200,
	dialog : { width : 600 , height : 300 }
      },
      prototype: {
	root: null,
	gen_form : function ( url,args,kind ) {
	  var t = this;
	  var html = '<form method="POST" style="padding:0;" class="'+t.settings.title+'" action="'+url+'" kind="'+kind+'"><table><tbody>';
	  html += '<input type="text" name="dummy" style="position:absolute;visibility:hidden"><input type="button" value="" onClick="" style="position:absolute;visibility:hidden">';
	  for ( arg in args ) {
	    html += '<div class="value"><input type="hidden" name="'+arg+'" value="'+args[arg]+'"></input></div>';
	  }
	  var form = t.settings.form;
	  for ( parm in form ) {
	    help = ''
	    if (form[parm].help) {
	      help = ' <a class="help">?<div class="helpmsg">'+form[parm].help+'</div></a>';
	    }
	    if ( form[parm].type == 'hidden' ) {
	      html += '<tr><td></td><td><div class="value"><input type="hidden" name="'+parm+'" value="'+(form[parm].def?form[parm].def:"")+'"></input></div></td></tr>';
	    } else if ( form[parm].type == 'text' || form[parm].type == 'html' ) {
	      html += '<tr><td><div class="label">'+form[parm].label+help+'</div></td><td><div class="value"><input type="text" name="'+parm+'" value="'+(form[parm].def?form[parm].def:"")+'"></input></div></td></tr>';
	    } else if ( form[parm].type == 'textarea' ) {
	      html += '<tr><td><div class="label">'+form[parm].label+help+'</div></td><td><div class="value"><textarea name="'+parm+'">'+(form[parm].def?form[parm].def:"")+'</textarea></div></td></tr>';
	    } else if ( form[parm].type == 'select' ) {
	      html += '<tr><td><div class="label">'+form[parm].label+help+'</div></td><td><div class="value"><select name="'+parm+'">';
	      for ( opt in form[parm].options ) {
		if ( form[parm].def && form[parm].def == form[parm].options[opt] ) {
		    html += '<option value="'+form[parm].options[opt]+'" selected="selected">'+opt+'</option>';
		}else{
		    html += '<option value="'+form[parm].options[opt]+'">'+opt+'</option>';
		}
	      }
	      html += '</select></div></td></tr>';
	    }
	  }
	  html += '</tbody></table></form>';
	  t.root.append(html);
	  t.root.find('form.'+t.settings.title+':last').bind('notice',function (ev) {
	    t.list();
	  });
	  t.root.find('form.'+t.settings.title+':last a.help').click(function (ev){
	    $(this).find('div.helpmsg').toggle();
	  });
	  t.root.find('form.'+t.settings.title+':last a.help > div.helpmsg').hide();
	},
	set_form : function () {
	  var t = this;
	  var form = t.root.find('form')
	  if ( t.data && t.data[t.index] ) {
	    for (d in t.data[t.index]){
	      form.find('div.value *[name="'+d+'"]').val(t.data[t.index][d]);
	    }
	  }else {
	    form.remove();
	  }
	},
	init: function () {
	  var t = this;
	  t.root.css('width','' + t.settings.width + 'px');
	  // Head
	  t.root.append('<h2 class="ui-widget-header ui-corner-tr ui-corner-tl">' + t.settings.title + '<div></div></h2><div class="list"></div>');
	  t.root.append('<b class="message"></b>');
	  // Custom1 button
	  if (t.settings.custom1 ) {
	    t.root.find('h2 > div').append('<a src="#" class="custom1">'+t.settings.custom1.label+'</a>' );
	    t.root.find('h2 > div > a.custom1').click(function (ev){
	      if ( t.settings.custom1.hook ) {
		var msg = t.settings.custom1.hook(t);
		if ( msg ) {
		  var m = t.root.find('.message');
		  m.hide().text(msg).slideDown(1000);
		  setTimeout(function(){ m.slideUp(1000);},3000);
		  return;
		}
	      }
	    });
	  }
	  // Add button
	  if (t.settings.add ) {
	    t.root.find('h2 > div').append('<a src="#" class="add">add</a>' );
	    t.root.find('h2 > div > a.add').click(function (ev){
	      if ( t.settings.add.hook ) {
		var msg = t.settings.add.hook(t);
		if ( msg ) {
		  var m = t.root.find('.message');
		  m.hide().text(msg).slideDown(1000);
		  setTimeout(function(){ m.slideUp(1000);},3000);
		  return;
		}
	      }
	      var args = $.extend( {}, t.settings.args,t.settings.add.args );
	      t.gen_form(t.settings.add.url,args,'add');
	      t.settings.dialog.title = 'Add to ' + t.settings.title;
	      t.root.find('form').dialog_form(t.settings.dialog,t.settings.validator);
	    });
	  }
	  // Del button
	  if (t.settings.del ) {
	    t.root.find('h2 > div').append('<a src="#" class="del">del</a>' );
	    t.root.find('h2 > div > a.del').click(function (ev){
	      if ( t.settings.del.hook ) {
		var msg = t.settings.del.hook(t);
		if ( msg ) {
		  var m = t.root.find('.message');
		  m.hide().text(msg).slideDown(1000);
		  setTimeout(function(){ m.slideUp(1000);},3000);
		  return;
		}
	      }
	      var args = $.extend( {}, t.settings.args,t.settings.del.args );
	      t.gen_form(t.settings.del.url,args,'del');
	      t.set_form();
	      t.root.find('form *:input').attr('readonly','readonly');
	      t.settings.dialog.title = 'Delete from ' + t.settings.title;
	      t.root.find('form').dialog_form(t.settings.dialog);
	    });
	  }
	  // Update button
	  if (t.settings.update ) {
	    t.root.find('h2 > div').append('<a src="#" class="update">update</a>' );
	    t.root.find('h2 > div > a.update').click(function (ev){
	      if ( t.settings.update.hook ) {
		var msg = t.settings.update.hook(t);
		if ( msg ) {
		  var m = t.root.find('.message');
		  m.hide().text(msg).slideDown(1000);
		  setTimeout(function(){ m.slideUp(1000);},3000);
		  return;
		}
	      }
	      var args = $.extend( {}, t.settings.args,t.settings.update.args );
	      t.gen_form(t.settings.update.url,args,'update');
	      t.set_form();
	      t.settings.dialog.title = 'Update ' + t.settings.title;
	      t.root.find('form').dialog_form(t.settings.dialog,t.settings.validator);
	    });
	  }
	  // Copy button
	  if (t.settings.copy ) {
	    t.root.find('h2 > div').append('<a src="#" class="copy">copy</a>' );
	    t.root.find('h2 > div > a.copy').click(function (ev){
	      if ( t.settings.copy.hook ) {
		var msg = t.settings.copy.hook(t);
		if ( msg ) {
		  var m = t.root.find('.message');
		  m.hide().text(msg).slideDown(1000);
		  setTimeout(function(){ m.slideUp(1000);},3000);
		  return;
		}
	      }
	      var args = $.extend( {}, t.settings.args,t.settings.copy.args );
	      t.gen_form(t.settings.copy.url,args,'copy');
	      t.set_form();
	      t.settings.dialog.title = 'Copy ' + t.settings.title;
	      t.root.find('form').dialog_form(t.settings.dialog,t.settings.validator);
	    });
	  }
	  // View
	  if (t.settings.view ) {
	    var html = '<div class="view"><table><tbody>';
	    var form = t.settings.form;
	    for ( parm in form ) {
	      if ( form[parm].type == 'hidden' ) {
		//
	      } else if ( form[parm].type == 'text' || form[parm].type == 'html' ) {
		html += '<tr><td><div class="label">'+form[parm].label+'</div></td><td><div class="value" name="'+parm+'"></div></td></tr>';
	      } else if ( form[parm].type == 'textarea' ) {
		html += '<tr><td><div class="label">'+form[parm].label+'</div></td><td><div class="value" name="'+parm+'"></div></td></tr>';
	      } else if ( form[parm].type == 'select' ) {
		html += '<tr><td><div class="label">'+form[parm].label+'</div></td><td><div class="value" name="'+parm+'"></div></td></tr>';
	      }
	    }
	    html += '</tbody></table></div>';
	    t.root.append(html);
	  }
	},
	list: function () {
	  var t = this;
	  var args = $.extend( {}, t.settings.args,t.settings.list.args );
	  $.ajax({
	    url: t.settings.list.url,
	    type: 'POST',
	    dataType: 'json',
	    data: args,
	    t: t,
	    success: function (data){
	      if ( 'emsg' in data ) {
		this.t.reset();
		var m = t.root.find('b.message');
		m.text(data.emsg).slideDown(1000);
		setTimeout(function(){ m.slideUp(1000);},3000);
		return;
	      }
	      this.t.reset(t.settings.args);
	      this.t.data = data;
	      this.t.relist();
	    }
	  });
	},
	reset: function (args) {
	  if ( args == undefined ) {
	    args = {}
	  }
	  var t = this;
	  t.settings.args = args;
	  t.data = null;
	  t.root.find('div.view div.value').text('');
	  t.root.find('div.list > *').remove();
	  delete(t.index);
	  t.settings.reset();
	},
	relist: function () {
	  var t = this;
	  var html = '';
	  var ndata = {};
	  var nkeys = [];
	  for (d in t.data){
	    var clazz = '';
	    if ( $.isFunction(t.settings.list.clazz) ){
	      clazz = t.settings.list.clazz(t.data[d]);
	    }
	    if ( clazz == null ) {
	      continue;
	    }
	    var p = html_encode(t.data[d][t.settings.list.col]);
	    var re = /(\S+\/)([^\/]+\/?)$/;
	    for(;;){
	      if ( ! p || p.match(/:\/\/[^\/]+\/$/) ) {
		nkeys.push(p);
		ndata[p] = { dc : 'P /' , c : clazz , i : d , p : p};
		break;
	      }
	      var m = p.match(re);
	      if ( ndata[p] ) {
		ndata[p].dc = ndata[p].dc + ' P';
		break;
	      }
//            $('html').append(p+'<br>');
	      nkeys.push(p);
	      if ( m ) {
		ndata[p] = { dc : ((d==='-')?'P ':'') + m[1] , c : clazz , i : d , p : p};
		clazz = "";
		d = "-";
		p = m[1];
		continue;
	      }else{
		// Reserve code... 
		ndata[p] = { dc : (d==='-')?'/ P':'/' , c : clazz , i : d , p : p};
	      }
	      break;
	    }
	  }
	  nkeys.sort();
	  for ( k in nkeys ) {
	    n = nkeys[k];
	    html += '<div class="'+ndata[n].dc+'" index="'+ndata[n].i+'"><a class="'+ndata[n].c+'" index="'+ndata[n].i+'">'+ndata[n].p+'</a></div>';
	  }
	  t.root.find('div.list').append(html);
	  t.root.find('div.list > div.P').click( function (ev){
	    selector = 'div.list > div.' + $(this).text().replaceAll('/','\\/').replaceAll(':','\\:').replaceAll('\.','\\\.');
	    t.root.find(selector).show();
	  });

	  t.root.find('div.list > div > a').not('[index="-"]').click(function(ev){
	    if ( !$(this).hasClass('selected')){
	      t.root.find('div.list > div > a').removeClass('selected');
		$(this).addClass('selected');
	      t.index = $(this).attr('index');
	      t.view();
	      t.settings.change(t.data[t.index]);
	    }
	  });
	},
	view: function () {
	  var t = this;
	  t.root.find('div.view div.value[name]').text('');
	  var form = t.settings.form;
	  for ( parm in form ) {
	    if ( t.data[t.index][parm] ) {
	      if ( form[parm].type == 'hidden' ) {
		//
	      } else if ( form[parm].type == 'text' ) {
		t.root.find('div.view div.value[name="'+parm+'"]').text(t.data[t.index][parm]);
	      } else if ( form[parm].type == 'html' ) {
		t.root.find('div.view div.value[name="'+parm+'"]').append(t.data[t.index][parm]);
	      } else if ( form[parm].type == 'textarea' ) {
		t.root.find('div.view div.value[name="'+parm+'"]').append('<pre></pre>');
	      //t.root.find('div.view div.value[name="'+parm+'"] > pre').text(t.data[t.index][parm]);
		t.root.find('div.view div.value[name="'+parm+'"] > pre').text(t.data[t.index][parm].replace(/\r?\n/g,"\r\n"));
	      } else if ( form[parm].type == 'select' ) {
		var text = '--';
		for ( value in form[parm].options ) {
		  if ( form[parm].options[value] == t.data[t.index][parm] ) {
		    text = value;
		    break;
		  }
		}
		t.root.find('div.view div.value[name="'+parm+'"]').text(text);
	      }
	    }
	  }
	}
      }
    });
  
    $.extend($.fn,{
      dialog_form: function(options,validator){
	if (!this.length) {
	  return;
	}
	return new $.DialogForm($(this),options,validator);
      }
    });
    $.DialogForm = function (node,options,validator) {
      var t = this;
      t.root  = node;
      t.settings = $.extend( {}, $.DialogForm.defaults, options );
      t.validator = validator;
      t.init();
    }
    $.extend($.DialogForm, {
      defaults: {
	bgiframe: true,
	modal: true,
	autoOpen: false,
	width : 600,
	height: 400,
	target: null,
	post_init: function ( root ) {}
      },
      prototype: {
	init: function (){
	  var t = this;
	  t.root.find('div.label').click( function (e){
	    if ( $(this).parent().next().is(':hidden') ) {
		$(this).parent().next().show();
	    }else {
		$(this).parent().next().hide();
	    }
	  });
	  t.settings.buttons = {
	      'OK' : function () {
		if ( t.settings.target == null ) {
		  var type = $(this).attr('method')?$(this).attr('method'):'POST';
		    $.ajax({
		      url: $(this).attr('action'),
		      type: type,
		      dataType: 'json',
		      data: $(this).serialize(),
		      form: $(this),
		      success : function ( data ) {
			if ( 'emsg' in data ) {
			    $('div.ui-dialog-titlebar').after('<b class="error">'+data.emsg+'</b>');
			  return;
			}
			this.form.trigger('notice');
			this.form.dialog('destroy');
			this.form.remove();
		      }
		    });
		}else {
		  $(this).trigger('notice');
		  $(this).dialog('destroy');
		  t.settings.target.children('form').remove();
		  $(this).appendTo(t.settings.target);
		}
	      },
	      'SYNC' : function () {
		if ( t.settings.target == null ) {
		  var type = $(this).attr('method')?$(this).attr('method'):'POST';
		  $(this).find('input[name="rev"]').val(''); // ignoring rev
		    $.ajax({
		      url: $(this).attr('action'),
		      type: type,
		      dataType: 'json',
		      data: $(this).serialize(),
		      form: $(this),
		      success : function ( data ) {
			if ( 'emsg' in data ) {
			    $('div.ui-dialog-titlebar').after('<b class="error">'+data.emsg+'</b>');
			  return;
			}
		      }
		    });
		}else {
		  $(this).trigger('notice');
		  $(this).dialog('destroy');
		  t.settings.target.children('form').remove();
		  $(this).appendTo(t.settings.target);
		}
	      },
	      'Cancel': function () {
		  //$(this).trigger('notice');
		  $(this).dialog('destroy');
		  $(this).remove();
	      }
	  };
	  if ( t.validator) {
	    t.form = t.root.validate(t.validator);
	    t.form.form();
	  }
	  t.root.find('*:input').change(function(ev){
	    t.form = t.root.validate(t.validator);
	    t.form.form();
	  });
	  /* t.root.keydown(function(ev){ */
	  /*   if (ev.keyCode == 13 ){ */
	  
	  /*   } */
	  /* }); */
	  t.settings.post_init(t.root);
	  t.root.dialog(t.settings);
	  t.root.dialog('open');
	}
      }
    });
})(jQuery);
  function html_encode(str){
    str=str.replace(/&/g,'&amp;');
    str=str.replace(/</g,'&lt;');
    str=str.replace(/>/g,'&gt;');
    str=str.replace(/\"/g,'&quot;');
    str=str.replace(/\'/g,'&#39;');
    return str;
  }
String.prototype.replaceAll = function (org, dest){  
  return this.split(org).join(dest);  
}  
