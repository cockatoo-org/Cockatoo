//var sys = require('sys');
var sys = require('util');
var fs  = require('fs');
var log     = require( __dirname + '/lib/log.js');
var stdtest = require( __dirname + '/lib/stdtest.js');
// all page
exports.get = function() { 
  return {
    TEST_NAME: 'Cockatoo-crawl-wiki',
    URL      : 'http://cockatoo.jp/',
    PROXY    : null, // <host>:<port>
    SSLPROXY : null, // Not supported
    TIMEOUT  : 5000, // msec
    WAIT     : 100,  // msec
    TEST : { 
      ON_ERROR : function on_error(pref,strurl,selector,msg,data){
	// log.error(strurl,selector,'HOOK error',msg);
      },
      STATUS   : stdtest.DEFAULT_CHECK_STATUS,
      REDIRECT : {
	FILTER : stdtest.DEFAULT_FILTER
      },
      CHECKS   :
	[{     // To be cockatoo framework.
	  METHOD   : 'EXIST',
	  SELECTOR : 'div.window',
	},{    // Compare URL with contents
          METHOD   : 'HOOK',
	  SELECTOR : 'body',
          HOOK     : function(test,strurl,remark,html_elem) {
	    if ( /New/.test(html_elem.find('h2').text()) ){
	      test.ON_ERROR('HOOK',strurl,html_elem.find('h2').text(),'Empty page',{From:remark});
	      return 'Empty page';
	    }
	    return '';
	  }
	},{    // Crawl linked wiki page
	  METHOD   : 'CRAWL',
	  SELECTORS: ['a'],
	  FILTER   : {
	    ERROR  : [],
	    IGNORE : [],
	    FOLLOW : ['http://cockatoo\.jp/wiki/view/'],
	    INNER_DOMAIN   : false
	  }
	}]
    }
  };
}
