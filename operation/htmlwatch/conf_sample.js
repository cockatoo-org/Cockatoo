//var sys = require('sys');
var sys = require('util');
var stdtest = require( __dirname + '/lib/stdtest.js');

// top page view check
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
	[{  // check page component
	  METHOD   : 'EXIST',
	  SELECTOR : 'div.page', 
	},{ // check login logic
	  METHOD   : 'NOT_EXIST',
	  SELECTOR : 'div.user', 
	},{
	  METHOD   : 'TEXT',
	  SELECTOR : 'div.wiki > h1',
	  EXPECTS  : /top/
	},{
	  METHOD   : 'TEXT',
	  SELECTOR : 'title',
	  EXPECTS  : /top/
	},{
	  METHOD   : 'LINK',
	  SELECTOR : 'a',
	  FILTER   : {
	    ERROR  : [],
	    IGNORE : [],
	    FOLLOW : [/https?:\/\//],
	    INNER_DOMAIN   : true
	  },
	  TEST     : stdtest.STATUS_TEST
	},{
	  METHOD   : 'LINK',
	  SELECTOR : 'link',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	},{
	  METHOD   : 'LINK',
	  SELECTOR : 'img',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	},{
	  METHOD   : 'LINK',
	  SELECTOR : 'script',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	}]
    }
  };
}
