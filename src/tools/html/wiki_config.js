//var sys = require('sys');
var sys = require('util');
var fs  = require('fs');
var stdtest = require( __dirname + '/lib/stdtest.js');

// top page view check
exports.get = function() { 
  return {
    TEST_NAME: 'Cockatoo-wiki',
    URL      : 'http://cockatoo.jp/',
    PROXY    : null, // <host>:<port>
    SSLPROXY : null, // Not supported
    TIMEOUT  : 5000, // msec
    WAIT     : 100,  // msec
    TEST : { 
      STATUS   : stdtest.DEFAULT_CHECK_STATUS,
      REDIRECT : {
	FILTER : stdtest.DEFAULT_FILTER
      },
      CHECKS   :
	[{  // check page component
	  METHOD   : 'EXIST',
	  SELECTOR : 'div.page', 
	},{ // check not be logined
	  METHOD   : 'NOT_EXIST',
	  SELECTOR : 'div.user', 
	},{ // H1 is the page title
	  METHOD   : 'TEXT',
	  SELECTOR : 'div.wiki > div.h1 > h1',
	  EXPECTS  : 'Cockatoo PHP framework'
	},{ // <title>
	  METHOD   : 'TEXT',
	  SELECTOR : 'title',
	  EXPECTS  : 'Cockatoo PHP framework'
	},{ // <title>
	  METHOD   : 'TEXT',
	  SELECTOR : 'title',
	  EXPECTS  : 'UNMATCH TEST'
	},{ // Status check  <a href=> contains external site.
	  METHOD   : 'LINK',
	  SELECTOR : 'a',
	  FILTER   : {
	    ERROR  : [],
	    IGNORE : [],
	    // FOLLOW : ['http://'],
	    INNER_DOMAIN   : true
	  },
	  TEST     : stdtest.STATUS_TEST
	},{ // Status check <link href=>
	  METHOD   : 'LINK',
	  SELECTOR : 'link',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	},{ // Status check <img src=>
	  METHOD   : 'LINK',
	  SELECTOR : 'img',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	},{ // Status check <script src=>
	  METHOD   : 'LINK',
	  SELECTOR : 'script',
	  FILTER   : stdtest.DEFAULT_FILTER,
	  TEST     : stdtest.STATUS_TEST
	}]
    }
  };
}
