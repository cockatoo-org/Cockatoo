;(function($) {
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
									t.settings.target.children('form').remove();
										$(this).appendTo(t.settings.target);
										$(this).trigger('notice');
										$(this).dialog('destroy');
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
