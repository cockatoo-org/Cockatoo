#!/usr/bin/env node
// Install these packages.
//   npm install getopt
//   npm install jsdom

//----------------------------------------------
// Node modules
//----------------------------------------------
//var sys = require('sys');
var sys = require('util');
var opt = require('getopt');
var crypto  = require('crypto');
var fs = require('fs');
var path = require('path');
var url = require('url');
//var querystring = require('querystring');
var http    = require('http');
var https   = require('https');
var jsdom   = require('jsdom').jsdom;

//----------------------------------------------
// Definitions
//----------------------------------------------
var MEM_THRESHOLD = 100*1024*1024; // byte
var DATA_DIR = __dirname + '/data';


//----------------------------------------------
// Options
//----------------------------------------------
var LOGLV   =  6;
var PROXY   = null;
var SSLPROXY= null;
var TIMEOUT = null;
var WAIT    = null;
var STD     = null;
var CRAWL   = null;
var URL     = null;
var CONF    = null;
var JQUERY  = 'jquery-1.4.4.js'
var RESUME  = null;
var WORKER  = null;
var VERBOSE = null;

function help(a){
  sys.puts('Usage:');
  sys.puts('   node htmlcheck.js -u <url>       [-p <proxy>] [-l loglv] [-j <jquery>]');
  sys.puts('   node htmlcheck.js -c <conf-file> [-p <proxy>] [-l loglv] [-j <jquery>]');
  sys.puts('Options:');
  sys.puts('   -l <loglv>     : Specify the loglv from 0 to 20 ( 6 is defalut )');
  sys.puts('   -p <proxy>     : Specify the http-proxy-url ( for example -p http://proxy.example.com:8080 )');
  sys.puts('   -s <ssl-proxy> : Specify the https-proxy-url ( for example -p http://proxy.example.com:8080 )');
  sys.puts('   -t <timeout>   : Specify the http timeout (msec)');
  sys.puts('   -w <wait>      : Specify the request wait (msec)');
  sys.puts('   -S             : Standerd test mode');
  sys.puts('   -C             : Crawl test mode');
  sys.puts('   -u <url>       : Specify the target-url ');
  sys.puts('   -c <conf-file> : Specify the config file path');
  sys.puts('   -j <jquery>    : Specify the jquery file ( jquery-1.4.4.js is default )');
  sys.puts('   -R             : Resume mode. if you want to resume prior WATCH is interrupted by anything.');
  sys.puts('   -V             : Verbose log if you want to preserve all contents .');
  process.exit(a);
}

try {
  opt.setopt('hl:p:s:u:w:t:c:j:SCRWV',process.argv);
  opt.getopt(function ( o , p ){
    switch (o) {
      case 'h':
      help(0);
      break;
      case 'l':
      LOGLV = p;
      break;
      case 'p':
      PROXY = ''+p;
      break;
      case 's':
      SSLPROXY = ''+p;
      break;
      case 't':
      TIMEOUT = p;
      break;
      case 'w':
      WAIT = p;
      break;
      case 'S':
      STD   = 1;
      break;
      case 'C':
      CRAWL = 1;
      break;
      case 'u':
      URL = ''+p;
      break;
      case 'c':
      CONF = ''+p;
      break;
      case 'j':
      JQUERY = ''+p;
      break;
      case 'R':
      RESUME = 1;
      break;
      case 'W':
      WORKER = 1;
      break;
      case 'V':
      VERBOSE = 1;
      break;
    }
  });
}catch ( e ) {
  sys.puts('Invalid option ! "' + e.opt + '" => ' + e.type);
  help(1);
}



var stdtest = require( __dirname + '/lib/stdtest.js');

var SETTING = {
  URL      : null,
  PROXY    : null,
  SSLPROXY : null,
  TIMEOUT  : 5000,
  WAIT     : 1000,
  TEST     : stdtest.STATUS_TEST,
  VERBOSE  : null
}

if ( CONF ) {
  conf = require(path.resolve(CONF));
  SETTING = conf.get();
}
// Override member on config
SETTING.URL     =(URL===null)?     SETTING.URL     :URL;
SETTING.PROXY   =(PROXY===null)?   SETTING.PROXY   :PROXY;
SETTING.SSLPROXY=(SSLPROXY===null)?SETTING.SSLPROXY:SSLPROXY;
SETTING.TIMEOUT =(TIMEOUT===null)? SETTING.TIMEOUT :TIMEOUT;
SETTING.WAIT    =(WAIT===null)?    SETTING.WAIT    :WAIT;
SETTING.TEST    =(STD===null)?     SETTING.TEST    :stdtest.STD_TEST;
SETTING.TEST    =(CRAWL===null)?   SETTING.TEST    :stdtest.STD_CRAWL_TEST;
SETTING.VERBOSE =(VERBOSE===null)? SETTING.VERBOSE :1;

//---------------
// Validate 
if ( ! SETTING.URL ) {
  sys.puts('=== NO URL ! ===' );
  help(1);
}

//---------------
// Environments
// process.chdir(__dirname);

//---------------
// Test name
var common = require(__dirname + '/lib/common.js');
common.mkdirp(DATA_DIR);

var md5sum = crypto.createHash('md5');
SETTING.TEST_NAME = (SETTING.TEST_NAME)? SETTING.TEST_NAME :md5sum.update(JSON.stringify(SETTING)).digest('hex');
var QFILE = DATA_DIR + '/' + SETTING.TEST_NAME + '.q';
var CFILE = DATA_DIR + '/' + SETTING.TEST_NAME + '.c';
var LOG   = DATA_DIR + '/' + SETTING.TEST_NAME + '.log';
var LDIR  = DATA_DIR + '/' + SETTING.TEST_NAME;

var log = require( __dirname + '/lib/log.js').log(LOG,LOGLV).init();
var Q   = require( __dirname + '/lib/fetch_queue.js').fetch_queue(QFILE).init();
var C   = require( __dirname + '/lib/fetch_check.js').fetch_check(CFILE).init();
var L   = require( __dirname + '/lib/fetch_logger.js').fetch_logger(LDIR).init(VERBOSE);
var TERM_FLG = false; // signal flag

//----------------------------------------------
// Control process ( parent )
//----------------------------------------------
if ( ! WORKER ) { 
  var child_process  = require('child_process');
  var cmd = process.argv.shift();
  process.argv.push('-W');

  process.on('SIGINT', function () {
    sys.puts('SIGINT Received !');
    TERM_FLG = true;
  });

  function fork_worker(){
    var worker = child_process.spawn(cmd,process.argv);
    worker.stdout.on('data',function(data){
      process.stdout.write(data);
    });
    worker.stderr.on('data',function(data){
      process.stderr.write(data);
    });
    worker.on('exit',function (code ) {
      log.echo(SETTING.URL,'Wait child ',code);
      if ( code === 2 && !TERM_FLG ) {
	fork_worker();
      }
    });
  }
  fork_worker();
  return;
}

//----------------------------------------------
// Worker process ( child )
//----------------------------------------------
process.on('SIGINT', function () {
  TERM_FLG = true;
});

//---------------
// Resume mode
if ( RESUME ) {
  Q.load();
  C.load();
  log.echo(SETTING.URL,'=== CONTINUE ===',SETTING.TEST_NAME);
}else{
  try { 
    fs.unlinkSync(QFILE);
  }catch(e){
  }
  try { 
    fs.unlinkSync(CFILE);
  }catch(e){
  }
  Q.push(SETTING.URL,SETTING.TEST,'-ROOT-');
  log.echo(SETTING.URL,'=== START ===',SETTING.TEST_NAME);
}

//---------------
// Fetch loop
try {
  log.echo('<URL>','<VAL>','<MSG>','<REMARK>');
  function add_queue ( url , test , remark ) {
    try{
      c = C.check(url,'Queuing');
      if ( c ) {
	log.message(url,c,'skip queuing');
	return;
      }
      Q.push(url,test,remark);
    }catch(e){
      log.error('=======================','LOOP','catch',e.stack);
    }
  }
  function loop (){
    try {
      if ( Q.length() ) {
	q = Q.pop();
	fetch_content(q.URL,q.TEST,q.REMARK,add_queue);
	var m = process.memoryUsage();
	log.echo(q.URL,Math.floor(m.heapUsed/(1024*1024)*100)/100 + ' / ' + Math.floor(m.heapTotal/(1024*1024)*100)/100 + ' (MB)','Q:' + Q.length());
	if (  TERM_FLG  || m.heapUsed >  MEM_THRESHOLD ) {
	  setTimeout(function(){
	    log.echo('=== Interrupt ===',m.heapUsed + ' > ' + MEM_THRESHOLD,SETTING.TEST_NAME);
	    process.exit(2);
	  },SETTING.TIMEOUT);
	} else {
	  if ( Q.length() ) {
	    setTimeout(loop,SETTING.WAIT);
	  }else {
	    setTimeout(loop,SETTING.TIMEOUT);
	  }
	}
      }
//    log.echo(SETTING.URL,'Normal finished ',' ===== ',' ===== ');
    }catch(e){
      log.error('=======================','LOOP','catch',e.stack);
    }
  }
  loop();
}catch ( e ) {
  log.error(SETTING.URL,'LOOP','catch',e.stack);
  return;
}
return;

//-----------------------------------
// 
//-----------------------------------
function fetch_content(strurl,TEST,remark,callback) {
  C.change(strurl,'Fetching');
  log.trace(strurl,'TRY');
  var parsed = url.parse(strurl);
  var request = null;
  if ( parsed.protocol === 'https:' ) {
    if ( SETTING.SSLPROXY ) {
      var px = /([^:]+):(\d+)/. exec(SETTING.SSLPROXY);
//      proxy = http.request({'host':px[1],'port':px[2],'path':parsed.hostname+':'+((parsed.port)?parsed.port:443),'method':'CONNECT'});
//      request = https.request({'host':parsed.host,'path':path,'method':'GET'});
//      request.connection = proxy.connection;
    }else{
      request = https.request({'host':parsed.host,'path':path,'method':'GET'});
    }
  }else {
    if ( SETTING.PROXY ) {
      var px = /([^:]+):(\d+)/. exec(SETTING.PROXY);
      log.debug('PROXY','GET: (' + px[1]+ ':'+px[2]+')  : ' + strurl);
      request = http.request({'host':px[1],'port':px[2],'path':strurl,'method':'GET'});
    } else {
      var path = parsed.pathname+((parsed.search)?parsed.search:'')
      log.debug('REQUEST','GET: (' + parsed.host+ ')  : ' + path);
      request = http.request({'host':parsed.host,'path':path,'method':'GET'});
    }
  }

  log.debug('REQUEST : '+strurl,request,['function']);

  function do_filter ( pre, target , FILTER ) {
    for ( var i in FILTER.ERROR ) {
      if ( FILTER.ERROR[i].test(target) ) {
	log.error(strurl,FILTER.ERROR[i],pre+' >BAD JUMP',target);
	TEST.ON_ERROR(pre,strurl,FILTER.ERROR[i],pre+' >BAD JUMP',target);
	return false;
      }
    }
    
    for ( var i in FILTER.IGNORE ) {
      if ( FILTER.IGNORE[i].test(target) ) {
	log.message(strurl,FILTER.IGNORE[i],pre+' >(ignore)',target);
	return false;
      }
    }
    
    for ( var i in FILTER.FOLLOW ) {
      if ( FILTER.FOLLOW[i].test(target) ) {
	return true;
      }
    }
    if ( FILTER.INNER_DOMAIN ) {
      var preq = url.parse(target);
      if ( preq.host === parsed.host ) {
	return true;
      }
    }
    log.message(strurl,'-',pre+' >(unmatch)',target);
    return false;
  }

  request.on('error',function(err){
    log.error(strurl,'HTTP ERROR',err);
    TEST.ON_ERROR('REQUEST',strurl,'HTTP ERROR',err);
  });
  
  request.on('response',function(res){
    log.status(strurl,res.statusCode,'STATUS');
    C.change(strurl,res.statusCode);
    L.status(strurl,res.statusCode);
    L.header(strurl,res.headers);

    if ( ! (res.statusCode in  TEST.STATUS) ){
      log.error(strurl,res.statusCode,'BAD STATUS 1');
      TEST.ON_ERROR('RESPONSE : ',strurl,res.statusCode,'BAD STATUS 1');
      return;
    }
    if ( res.statusCode === 302 || res.statusCode === 301 ) {
      var location = url.resolve(strurl,res.headers['location']);
      log.trace(location,'LOCATION');
      if ( strurl === location ) {
	log.error(strurl,location,'CIRCULAR LOCATION');
	TEST.ON_ERROR('REDIRECT',strurl,location,'CIRCULAR LOCATION');
	return;
      }
      if ( do_filter('REDIRECT',location,TEST.REDIRECT.FILTER) ){
	callback(location,TEST,strurl);
	return;
      }
      return;
    }
    if ( TEST.CHECKS.length === 0 ){
      log.ok(strurl,res.statusCode,'status ok');
      return;
    }
    res.setEncoding('utf8');
    var body = '';
    res.on('data',function(chunk){
      body = body + chunk;
    });
    res.on('end',function(){
      L.body(strurl,body);
      log.debug('RESPONSE:'+strurl,res,['function']);
      // res.destroy(); // @@@ 
      var content_type = res.headers['content-type'];
      if ( ! /html/.test(content_type)  ) {
	log.ok(strurl,content_type,'fetch ok');
	return;
      }
      if ( ! body ) {
	log.error(strurl,content_type,'NO BODY',res.statusCode);
	TEST.ON_ERROR('BODY',strurl,content_type,'NO BODY',res.statusCode);
	return;
      }
      // The error that "TypeError: Cannot call method 'call' of undefined" often occurs. It's jsdom has some bug ? (conflecting body's script ?)
      try {
	body = '<html><body id="ROOT">'+body+'</body></html>';
	var document = jsdom(body,null,{
	  features:{
	    FetchExternalResources : false,
	    ProcessExternalResources : false,
	      "MutationEvents"           : '2.0',
	      "QuerySelector"            : false
	  }
	});
	var window = document.createWindow();
//	document.cookie=res.headers['set-cookie'].toString();
	jsdom.jQueryify(window, JQUERY, function (window, $) {
	  try{
	    function uniq( arr ) {
	      var buf = {};
	      return arr.filter(function (e){
		if ( buf[e] ) {
		  return false;
		}
		buf[e] = true;
		return true;
	      });
	    }
	    
	    function uniq_links( elements, links ) {
	      if ( ! links ){
		links = [];
	      }
	      var re = /(^[^#]+)#.*$/;
	      elements.each( function(){
		var href = $(this).attr('href');
		if ( href ) {
		  links.push(url.resolve(strurl,href).replace(re,"$1"));
		}
		var src = $(this).attr('src');
		if ( src ) {
		  links.push(url.resolve(strurl,src).replace(re,"$1"));
		}
	      });
	      return uniq(links).sort().reverse();
	    }
	    for( var i in TEST.CHECKS ) {
	      try {
		var CHECK = TEST.CHECKS[i];
		if ( CHECK.METHOD == 'HOOK' ) {
		  try { 
		    var msg = CHECK.HOOK(TEST,strurl,remark,$('#ROOT > html'));
		    if ( msg  ) {
		      log.message(strurl,'HOOK','interrupt by hook',msg);
		      return;
		    }
		  }catch(e){
		    log.error(strurl,'','HOOK error',e.stack);
		  }
		  log.ok(strurl,remark,'callback');
		}else if ( CHECK.METHOD == 'CRAWL' ) {
		  log.ok(strurl,remark,'crawl ok');
		  var links = [];
		  for ( var s in CHECK.SELECTORS ) {
		    links = uniq_links($('#ROOT > html').find(CHECK.SELECTORS[s]),links);
		  }
		  for ( var l in links ) {
		    if ( do_filter('CRAWL',links[l],CHECK.FILTER )){
		      callback(links[l],TEST,strurl);
		    }
		  }
		} else {
		  var elements = $('#ROOT > html').find(CHECK.SELECTOR);
 		  log.debug('CHECK('+CHECK.METHOD+')',CHECK.SELECTOR); 
		  if ( CHECK.METHOD == 'EXIST' ) {
		    var size = elements.size();
		    if ( size === 0 ) {
		      throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'ELEMENT NOT FOUND','(not found)'];
		    }else {
		      log.ok(strurl,CHECK.SELECTOR,'exist ok','');
		    }
		  }else if ( CHECK.METHOD == 'NOT_EXIST' ) {
		    var size = elements.size();
		    if ( size !== 0 ) {
		      throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'UNEXPECTED ELEMENT FOUND',{Actual:elements.html()}];
		    }else {
		      log.ok(strurl,CHECK.SELECTOR,'not exist ok','');
		    }
		  }else if ( CHECK.METHOD == 'TEXT' ) {
		    var text = elements.text();
		    if ( ! CHECK.EXPECTS.test(text) ) {
		      throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'TEXT UNMATCH',{Expects:CHECK.EXPECTS,Actual:text}];
		    }else {
		      log.ok(strurl,CHECK.SELECTOR,'text ok');
		    }
		  }else if ( CHECK.METHOD == 'LINK' ) {
		    var links = uniq_links(elements);
		    for ( var l in links ) {
		      if ( do_filter('LINK',links[l],CHECK.FILTER )){
			callback(links[l],CHECK.TEST,strurl);
		      }
		    }
		  }else{
		    throw [CHECK.METHOD,strurl,CHECK.METHOD,'UNKNOWN CHECK',''];
		  }
		}
	      }catch(e){
		log.error(e[1],e[2],e[3],e[4]);
		TEST.ON_ERROR(e[0],e[1],e[2],e[3],e[4]);
	      }
	    }
	  }catch(e){
	    log.error(strurl,'jQueryify','Fatal error',e.stack);
	  }
	}); // jquery-1.4.4.js
      }catch(e){
	log.error(strurl,content_type,'INVALID HTML',e.stack);
	TEST.ON_ERROR(CHECK.METHOD,strurl,content_type,'INVALID HTML',e);
	return;
      }
    });
  });
  
  setTimeout(function () {
    if ( request ) {
      request.abort();
    }
    if ( C.timeout(strurl) ) {
      log.error(strurl,'' + SETTING.TIMEOUT + ' (ms)','TIMEOUT');
      TEST.ON_ERROR('TIMEOUT',strurl,'' + SETTING.TIMEOUT + ' (ms)','TIMEOUT');
    }
  },SETTING.TIMEOUT);
  request.end();
}

/*
export NODE_PATH=/usr/local/nodejs/lib/node_modules 
node htmlmon.js -u http://cockatoo.jp     -l 20
node htmlmon.js -u http://cockatoo.jp -S  -l 15
node htmlmon.js -u http://cockatoo.jp -C  -l 8
node htmlmon.js -c ./conf_sample.js       -l 10
node htmlmon.js -c ./conf_crawl_sample.js -l 10
*/

// Todo: @@@
//  set-cockiee
//  post
