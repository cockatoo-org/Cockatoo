//var sys = require('sys');
var sys = require('util');
var fs  = require('fs');
var common  = require( __dirname + '/lib/common.js');
var stdtest = require( __dirname + '/lib/stdtest.js');
var log     = require( __dirname + '/lib/log.js');

var errlog = 'Cockatoo-crawl-wiki.err';
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
      ON_ERROR : on_error,
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
	    page = strurl.replace(/^http:\/\/cockatoo\.jp\/view(\/.*)?/,"$1").replace(/^\?page\=(.*)/,"$1");
	    if ( ! page ) {
	      page = 'top';
	    }else {
	      page = page.replace(/^\//,'');
	    }
	    page_name = html_elem.find('h1').text();
	    if ( page !== page_name ) {
	      test.ON_ERROR('HOOK',strurl,page,'Invalid view',{From:remark,Name:page_name});
	      return 'Invalid view';
	    }
	    if ( /New/.test(html_elem.find('h2').text()) ){
	      test.ON_ERROR('HOOK',strurl,page,'Empty page',{From:remark});
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
	    FOLLOW : [/http:\/\/cockatoo\.jp\/view/],
	    INNER_DOMAIN   : false
	  },
	}]
    }
  };
}
