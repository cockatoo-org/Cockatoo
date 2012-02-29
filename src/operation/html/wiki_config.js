//var sys = require('sys');
var sys = require('util');
var fs  = require('fs');
var common  = require( __dirname + '/lib/common.js');
var stdtest = require( __dirname + '/lib/stdtest.js');
var log     = require( __dirname + '/lib/log.js');

var errlog = 'Cockatoo-wiki.err';
try{
  fs.unlinkSync(errlog);
}catch(e){
}
function on_error(pref,strurl,selector,msg,data){
  var fp = fs.openSync(errlog,'a+');
  var rec = common.padding(pref,10)+':'+common.padding(strurl,60)+': '+msg;
  if ( typeof(data) === 'object' ) {
    common.crawl_object(data,log.crawl_callback(function(line){
      rec += line+'\n';
    }));
  }else{
    rec += ' : ' + data;
  }
  rec +='\n';
  sys.puts(rec);
//  log.dump('','',data);
  fs.writeSync(fp,rec,null);
  fs.closeSync(fp);
  return '';
}

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
      // ON_ERROR : stdtest.NULL_ON_ERROR,
      ON_ERROR : on_error,
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
	  SELECTOR : 'div.wiki > h1',
	  EXPECTS  : /top/
	},{ // <title>
	  METHOD   : 'TEXT',
	  SELECTOR : 'title',
	  EXPECTS  : /top/
	},{ // Status check  <a href=> contains external site.
	  METHOD   : 'LINK',
	  SELECTOR : 'a',
	  FILTER   : {
	    ERROR  : [],
	    IGNORE : [],
	    FOLLOW : [/http:\/\//],
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
