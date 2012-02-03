;(function($) {
    $.validator.addMethod('hostname',
	  		  function(value,elem,parm) {
	  		    return this.optional(elem) || /^[a-zA-Z0-9\-_]+$/.test(value);
	  		  }, 
			  "Enter valid hostname");
    $.validator.addMethod('nospace',
	  		  function(value,elem,parm) {
	  		    return this.optional(elem) || /^[\S]+$/.test(value);
	  		  }, 
			  "Space is not allowed");
    $.validator.addMethod('ftboth',
	  		  function(value, elem,parm) {
			    return value != '' || $(this.currentForm).find('*:input[name='+parm+']').val() == '';
	  		  }, 
			  '[from] and [to] are pairs.');
    $.validator.addMethod('datetime',
	  		  function(value,elem,parm) {
	  		    return this.optional(elem) || /^20\d\d-\d\d-\d\d \d\d:\d\d$/.test(value);
	  		  }, 
			  "20YY-MM-DD hh:mm");
    $.validator.addMethod('time',
	  		  function(value, elem,parm) {
	  		    return this.optional(elem) || /^\d\d:\d\d$/.test(value);
	  		  }, 
			  "hh:mm");
    $.validator.addMethod('css_size',
	  		  function(value, elem,parm) {
	  		    return this.optional(elem) || /^((auto)|0|[\d]+(px|%))$/.test(value);
	  		  }, 
			  "0 | auto | ??px | ??%");
    $.validator.addMethod('margin',
	  		  function(value, elem,parm) {
	  		    return this.optional(elem) || /^(0|[\d]+(px|%))(\s+(0|[\d]+(px|%)))?(\s+(0|[\d]+(px|%)))?(\s+(0|[\d]+(px|%)))?$/.test(value);
	  		  }, 
			  "0 | auto | ??px | ??%");
    $.validator.addMethod('brls',
	  		  function(values, elem,parm) {
			    if ( this.optional(elem) ) {
			      return this.optional(elem);
			    }
			    vs = values.split("\n");
			    for ( i in vs ) {
			      value = vs[i];
			      if ( ! /^(action:\/\/[^\/]+\/[^\?&#]+\?[^#]+(#.*)?)?$/.test(value) ) {
				return false;
			      }
			    }
	  		    return true;
	  		  }, 
			  "action://???");
    $.validator.addMethod('brl',
	  		  function(value, elem,parm) {
	  		    return this.optional(elem) || /^(action:\/\/[^\/]+\/[^\?&#]+(\?[^#]+(#.*)?)?)?$/.test(value);
	  		  }, 
			  "action://???");
    $.validator.addMethod('regex',
    	  		  function(value, elem,parm) {
			    try{ return this.optional(elem) || "".match(value) || true
			    }catch(e){return false;}
    	  		  },
    			  'Invalid reguler expression.');
    $.validator.addMethod('emails',
			  function(value, element) {
			    return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?(,((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)*$/i.test(value);
                          },
    			  'Enter valid emails.');
})(jQuery);
