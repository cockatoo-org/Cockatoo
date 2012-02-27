//var sys = require('sys');
var sys = require('util');
var stdtest = require( __dirname + '/lib/stdtest.js');

// all page
exports.get = function() { 
  return {
    URL      : 'http://cockatoo.jp/',
    PROXY    : null, // <host>:<port>
    SSLPROXY : null, // Not supported
    TIMEOUT  : 5000, // msec
    WAIT     : 100,  // msec
    TEST : { 
      ON_ERROR : stdtest.NULL_ON_ERROR,
      STATUS   : stdtest.DEFAULT_CHECK_STATUS,
      REDIRECT : {
	FILTER : stdtest.DEFAULT_FILTER
      },
      CHECKS   :
	[{     // To be cockatoo framework.
	  METHOD   : 'EXIST',
	  SELECTOR : 'div.window',
	},{    // Check logic
          METHOD   : 'HOOK',
	  SELECTOR : 'body',
          HOOK     : function(test,strurl,html_elem) {
	    page = strurl.replace(/^http:\/\/cockatoo\.jp\/view(\/.*)?/,"$1");
	    if ( ! page ) {
	      page = 'top';
	    }else{
	      page = page.replace(/^\//,'');
	    }
	    page_name = html_elem.find('h1').text();
	    if ( page === page_name ) {
	      return '';
	    }
	    test.ON_ERROR(test,strurl,page,'Invalid view',page_name);
	    return 'Invalid view';
	  }
	},{	  
	  METHOD   : 'CRAWL',
	  SELECTORS: ['a'],
	  FILTER   : {
	    ERROR  : [],
	    IGNORE : [],
	    FOLLOW : [/http:\/\/cockatoo\.jp\/view/],
	    INNER_DOMAIN   : false
	  },
	}]
    }
  };
}
