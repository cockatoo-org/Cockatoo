<?php
namespace wiki;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * GetPageAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class PageAction extends \Cockatoo\Action {
  public function proc(){
    try{
      //list($P,$D,$C,$p,$m,$q,$c) = \Cockatoo\parse_brl($this->BRL);
      // 
      $this->setNamespace('wiki');

      $session = $this->getSession();
      $page   = $this->args['P'];
      $name   = $this->args['N'];
      $user = $session['login']['user'];
      // Query strings
      $op = $session[\Cockatoo\Def::SESSION_KEY_POST]['op'];
      if ( ! $op ) {
        $op = 'get';
      }
      if ( $op === 'get' ) {
        $this->updateSession(array('wiki' => $session['wiki'] ) );
        $pdata = Lib::get_page($page);
        if ( $pdata ) {
          return array( 'page' => $pdata);
        }else {
          $origin = '*New';
          $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
          return array( 'page' =>
                        Lib::page($page,
                                  $origin,
                                  $contents,
                                  $user));
        }
      }elseif( $op === 'preview' ) {
        $origin   = $session[\Cockatoo\Def::SESSION_KEY_POST]['origin'];
        $lines = explode("\n",$origin);
        $parser = new WikiParser($page,$lines);
        return array( 'page' => 
                      Lib::page($page,
                                $origin,
                                $parser->parse(),
                                $user));
      }elseif( $op === 'save' ) {
        if ( ! $user ) {
          throw new \Exception('You have to login before update wiki !!');
        }
        $origin   = $session[\Cockatoo\Def::SESSION_KEY_POST]['origin'];
        $lines = explode("\n",$origin);
        $parser = new WikiParser($page,$lines);
        $pdata = Lib::page($page,
                           $origin,
                           $parser->parse(),
                           $user);
        Lib::save_page($page,$pdata);
        $this->save_history($page,$user,'EDIT');
        $this->setRedirect('/view/'.$page);
        return;
      }elseif( $op === 'move' ) {
        if ( ! $user ) {
          throw new \Exception('You have to login before update wiki !!');
        }
        $new = $session[\Cockatoo\Def::SESSION_KEY_POST]['new'];
        if ( $new ) {
          $pdata = Lib::get_page($page);
          if ( $pdata ) {
            $pdata['title'] = $new;
            $lines = explode("\n",$pdata['origin']);
            $parser = new WikiParser($page,$lines);
            $pdata['contents'] = $parser->parse();
            Lib::save_page($new,$pdata);
            $this->move_image($new,$page);
            Lib::remove_page($page);
            $this->save_history($new,$user,'MOVE from ' . $page ) ;
            $this->setRedirect('/view/'.$new);
            return;
          }
          $this->setRedirect('/view');
        }else{
          $this->setRedirect('/view/'.$page);
        }
        return;
      }
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/error');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
    }
  }
  private function move_image($new,$page){
    $olds = array();
    $news = array();
    $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, '', \Cockatoo\Beak::M_KEY_LIST);
    $bret = \Cockatoo\BeakController::beakQuery(array($brl));
    foreach ( $bret[$brl] as $name ) {
      $old = \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, $name, \Cockatoo\Beak::M_DEL);
      $olds []= $old;
      $obrl  = \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, $name, \Cockatoo\Beak::M_GET);
      $oret = \Cockatoo\BeakController::beakQuery(array($obrl));
      if ( $oret[$obrl] ) {
        $nset = \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $new, $name, \Cockatoo\Beak::M_SET);
        $news []= array($nset,$oret[$obrl]);
      }
    }
    $bret = \Cockatoo\BeakController::beakQuery($news);
    $bret = \Cockatoo\BeakController::beakQuery($olds);
  }


  private function save_history($page,$user,$op){
    $date = strftime('%Y%m%d',time());
    $now = strftime('%Y/%m/%d %H:%M:%S',time());
    // get
    $hist = array();
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','hist','/CUR',\Cockatoo\Beak::M_GET,array(),array());
    $bret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $bret[$brl] and $bret[$brl]['hist']) {
      krsort($bret[$brl]['hist']);
      $hist = $bret[$brl];
      $hist['hist'] = array_slice($hist['hist'],0,10);
    }else {
      $hist = array();
    }
    // save history
    $hist ['hist'][$now]= array('title' => $page , 'author' => $user , 'op' => $op);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','hist','/'.$date,\Cockatoo\Beak::M_SET,array(),array());
    $bret = \Cockatoo\BeakController::beakQuery(array(array($brl,$hist)));
    // save current history
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','hist','/CUR',\Cockatoo\Beak::M_SET,array(),array());
    $bret = \Cockatoo\BeakController::beakQuery(array(array($brl,$hist)));
  }
  public function postProc(){
  }
}

class WikiParser {
  public function __construct(&$page,&$lines){
    $this->page  = &$page;
    $this->lines = &$lines;
    $this->headers;
  }
  public function parse() {
    $body  = $this->parseHeads(1);
    $ibody = array('tag' => 'div','attr' => array('class'=>'ih'),'children' => array($this->parseIndexes(1)));
//print '<pre>';
//var_dump($ibody);
//print '</pre>';
    return array($ibody,$body);
  }

  private static function tag( $tag , $attr = array() , $children = array() ) {
    if ( is_array($children) ) {
      $ret = array('tag' => $tag ,'attr' => $attr,'children' => array() );
      foreach( $children as $child ) {
        if ( is_array($child) ) {
          $ret['children'] []= $child;
        }else{
          $ret['children'] []= array('tag' => 'text', 'text' => $child);
        }
      }
      return $ret;
    }else{
      return array('tag' => $tag ,'attr' => $attr,'children' => array(array('tag' => 'text', 'text' => $children)) );
    }
  }


  private function parseIndexes($heading){
    if ( $heading < 3 ) {
      $ibody = self::tag('ol',array('class'=>'ih'.($heading+1)));
    }else{
      $ibody = self::tag('ul',array('class'=>'ih'.($heading+1)));
    }
    while ( ($header = array_shift($this->headers)) !== null ) {
//      print '<pre>'.$header[0] . ' : ' . $heading . ' : ' . $index . ' : ' . $header[1] .'</pre>';
      if ( $header[0]  > $heading) {
        array_unshift($this->headers,$header);
        $ibody['children'] []= $this->parseIndexes($heading+1);
      }elseif ( $header[0] === $heading ){
        $ibody['children'] []= self::tag('li',array('class'=>'ih'),array(
                                           self::tag('a',array('href'=>'#'.$header[1]),$header[1])));
      }else {
        array_unshift($this->headers,$header);
        break;
      }
    }
    return $ibody;
  }

  private function pop_line(){
    $line = array_shift($this->lines);
    if ( $line === null ) {
      return null;
    }
    return chop($line,"\r\n");
  }
  private function push_line($line){
    return array_unshift($this->lines,$line."\n");
  }

  private function parseHeads($heading) {
    $body = array('tag' => 'div','attr' => array('class'=>'hd'.$heading),'children' => array());
    while ( ($line = $this->pop_line()) !== null ) {
      if ( preg_match('@^(\*+)(.*)@', $line , $matches ) !== 0 ) {
        //H?
        $h=strlen($matches[1]);
        if ( $h > $heading ) {
          $this->push_line($line);
          $body['children'] []= $this->parseHeads($heading+1);
        }elseif ( $h === $heading ){
          $this->headers []= array($heading,$matches[2]);
          $name = ltrim(chop($matches[2]));
          $body['children'] []= self::tag('div',array('class'=>'h'.($heading+1)),array(
                                            self::tag('h'.($heading+1),array(),array(
                                                        $name,
                                                        self::tag('a',array('href' => '#'.$name, 'name' => $name),'+')))));
          $body['children'] []= $this->parseHeads($heading+1);
        }else{
          $this->push_line($line);
          break;
        }
      }else{
        $this->push_line($line);
        $body['children'] = $this->parseContents();
      }
    }
    return $body;
  }
  
  private function parseContents($flg=0){
    $body = array();
    while ( ($line = $this->pop_line()) !== null ) {
      if ( preg_match('@^(\*+)(.*)@', $line , $matches ) !== 0 ) {
        $this->push_line($line);
        break;
      }else{
        if ( preg_match('@^ (.*)@', $line , $matches ) !== 0 ) {
          //PRE
          $text = $matches[0] . "\n";
          while ( ($line = $this->pop_line()) !== null ) {
            if ( preg_match('@^ (.*)@', $line , $matches ) !== 0 ) {
              $text .= $matches[0] . "\n";
            }else{
              $this->push_line($line);
              break;
            }
          }
          $body []= self::tag('pre',array(), $text);
        }elseif ( preg_match('@^(-----)@', $line , $matches ) !== 0 ) {
          //HR
          $body []= self::tag('hr');
        }elseif ( preg_match('@^>>(.*)@', $line , $matches ) !== 0 ) {
          //BLOCKQUOTE
          $blockquote = self::tag('blockquote',array(),$this->parse_inner($matches[1]));
          
          while ( ($line = $this->pop_line()) !== null ) {
            if ( preg_match('@^>>(.*)@', $line , $matches ) !== 0 ) {
              $blockquote['children'] = array_merge($blockquote['children'],array(self::tag('br')),$this->parse_inner($matches[1]));
            }else{
              $this->push_line($line);
              break; // End of BLOCKQUOTE
            }
          }
          $body []= $blockquote;

        }elseif ( preg_match('@^:([^:]+):(.*)@', $line , $matches ) !== 0 ) {
          // DL DT DD
          $dl = self::tag('dl',array(),array(
                            self::tag('dt',array(),$this->parse_inner($matches[1]))));
          $dd = self::tag('dd',array(),$this->parse_inner($matches[2]));

          while ( ($line = $this->pop_line()) !== null ) {
            if ( ! $line ) {  // End of DL
              $dl['children'] []= $dd;
              break;
            }elseif ( preg_match('@^:(.*)@', $line , $matches ) !== 0 ) { // Next DD
              $dl['children'] []= $dd;
              $dd = self::tag('dd',array(),$this->parse_inner($matches[1]));
            }else{
              $dd['children'] = array_merge($dd['children'],array(self::tag('br')),$this->parse_inner($line));
            }
          }
          $body []= $dl;
        }elseif ( preg_match('@^(-+)(.*)@', $line , $matches ) !== 0 ) {
          //UL
          $n = strlen($matches[1]);
          if ( $n > $flg ) {
            $this->push_line($line);
            $body []= self::tag('ul',array('class'=>'ul'.$flg),$this->parseContents($flg+1));
          }elseif ( $n === $flg ){
            $body []= self::tag('li',array('class'=>'ul'.$flg),$this->parse_inner($matches[2]));
          }else{
            $this->push_line($line);
            break;
          }
        }elseif ( preg_match('@^(\++)(.*)@', $line , $matches ) !== 0 ) {
          //OL
          $n = strlen($matches[1]);
          if ( $n > $flg ) {
            $this->push_line($line);
            $body []= self::tag('ol',array('class'=>'ol'.$flg),$this->parseContents($flg+1));
          }elseif ( $n === $flg ){
            $body []= self::tag('li',array('class'=>'ol'.$flg),$this->parse_inner($matches[2]));
          }else{
            $this->push_line($line);
            break;
          }
        }elseif ( $flg and ! $line ) {
          $this->push_line($line);
          break;
        }else{
          $body []= self::tag('text',array(),array_merge($this->parse_inner($line),array(array('tag' => 'br','text' => ''))));
        }
      }
    }
    return $body;
  }
  
  private function parse_inner(&$line){
    $body = array();
    $text = $line;
    for(;;){
      if ( preg_match('@^([^&\[]*)\[\[([^\]|]+)((?:\|[^\]]+)?)\]\](.*)@', $text , $matches ) !== 0 ) {
        // A   => [<text>|<link or url>]
        $body [] = self::tag('text',array(),$matches[1]);
        if ( preg_match('@^https?://@', $matches[2] , $matchdummy ) !== 0 ) {
          $body [] = self::tag('a',array('href' => $matches[2]),(($matches[3])?ltrim($matches[3],'|'):$matches[2]));
        }elseif(preg_match('@^#@', $matches[2] , $matchdummy ) !== 0 ) {
          $body [] = self::tag('a',array('href' => $matches[2]),(($matches[3])?ltrim($matches[3],'|'):$matches[2]));
        }else{
          $body [] = self::tag('a',array('href' => '/view/' . $matches[2]),(($matches[3])?ltrim($matches[3],'|'):$matches[2]));
        }
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^([^&\[]*)&ref\(([^\),]*)(?:,(\d*)(?:,(\d*))?)?\);(.*)@', $text , $matches ) !== 0 ) {
        // IMG => &ref(<image>,<height>,<width>);
        $body [] = self::tag('text',array(),$matches[1]);
        if ( preg_match('@^https?://@', $matches[2] , $matchdummy ) !== 0 ) {
          $attr = array('src' => $matches[2]);
        }else {
          $attr = array('src' => '/img/'.$this->page.'?n='.$matches[2]);
        }
        if ( $matches[3] ) {
          $attr['height'] = $matches[3];
        }
        if ( $matches[4] ) {
          $attr['width'] = $matches[4];
        }
        $body [] = self::tag('a',array('href' => '/img/'.$this->page.'?n='.$matches[2]),array(self::tag('img',$attr)));
        $text = $matches[5];
        next;
      }elseif ( preg_match('@^([^&\[]*)&color\(([^\)]*)\)\{([^\}]*)\};(.*)@', $text , $matches ) !== 0 ) {
        // COLOR => &color(<color>){<text>};
        $body [] = self::tag('text',array(),$matches[1]);
        $body [] = self::tag('span',array('style' => 'color:' . $matches[2]),$matches[3]);
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^([^&\[]*)&b\(([^\)]*)\)\{([^\}]*)\};(.*)@', $text , $matches ) !== 0 ) {
        // BOLD => &b(<level>){<text>};
        $body [] = self::tag('text',array(),$matches[1]);
        $body [] = self::tag('b',array('class' => 'b' . $matches[2]),$matches[3]);
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^([^&\[]*)&anchor\(([^\)]*)\);(.*)@', $text , $matches ) !== 0 ) {
        // ANCHOR => &anchor(<name>);
        $body [] = self::tag('text',array(),$matches[1]);
        $body [] = self::tag('a',array('href' => '#'.$matches[2], 'name' => $matches[2]),'+');
        $text = $matches[3];
        next;
      }elseif ( preg_match('@^([^&\[]*)&del\{([^\}]*)\};(.*)@', $text , $matches ) !== 0 ) {
        // DEL => &del{};
        $body [] = self::tag('text',array(),$matches[1]);
        $body [] = self::tag('del',array(),$matches[2]);
        $text = $matches[3];
        next;
      }elseif ( preg_match('@^(.*?) && (.*)@', $text , $matches ) !== 0 ) {
        // BR =>
        $body [] = self::tag('text',array(),$matches[1]);
        $body [] = self::tag('br');
        $text = $matches[2];
      }else{
        $body [] = self::tag('text',array(),$text);
        break;
      }
    }
    return $body;
  }
}
