<?php
namespace mongo;

class PageParser {
  public function __construct(&$page,&$lines){
    $this->page  = &$page;
    $this->lines = &$lines;
    $this->headers;
  }
  public function parse() {
    $body  = $this->parseHeads(1);
    return array($body);
/*
    $ibody = array('tag' => 'div','attr' => array('class'=>'ih'),'children' => array(
                     self::tag('a',array('class'=>'visible toggle','href'=>'#'),''),
                     $this->parseIndexes(1)));
    return array($ibody,$body);
*/
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
      if ( $header[0]  > $heading ) {
        if ( $heading < 4 ) {
          array_unshift($this->headers,$header);
          $ibody['children'] []= $this->parseIndexes($heading+1);
        }
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
          $name = ltrim(chop($matches[2]));
          $this->headers []= array($heading,$name);
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
          $text = $matches[1] . "\n";
          while ( ($line = $this->pop_line()) !== null ) {
            if ( preg_match('@^ (.*)@', $line , $matches ) !== 0 ) {
              $text .= $matches[1] . "\n";
            }else{
              $this->push_line($line);
              break;
            }
          }
          $body []= self::tag('pre',array(), $text);
        }elseif ( preg_match('@^---+$@', $line , $matches ) !== 0 ) {
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

  private static function parse_brackets($bre){
    $n = strlen($bre);
    $nest = 0;
    $i = 0;
    for( ; $i < $n ; $i++ ) {
      $c = substr($bre,$i,2);
      if ( preg_match('@^{@',$c,$m) !== 0) {
        $nest++;
      }elseif ( $c === '};' ) {
        $nest--;
      }
      if ( $nest === 0 ){
        return array(substr($bre,1,$i-1),substr($bre,$i+2));
      }
    }
    return array(substr($bre,1),'');
  }
  
  private function parse_inner(&$line){
    $body = array();
    $text = $line;
    for(;;){
      if ( preg_match('@^([^\[]*)\[\[([^\]|]+)((?:\|[^\]]+)?)\]\](.*)@', $text , $matches ) !== 0 ) {
        // A   => [<text>|<link or url>]
        $body = array_merge($body,$this->parse_inner($matches[1]));
        $text = (($matches[3])?ltrim($matches[3],'|'):$matches[2]);
        $children = $this->parse_inner($text);
        if ( preg_match('@^https?://@', $matches[2] , $matchdummy ) !== 0 ) {
          $body [] = self::tag('a',array('href' => $matches[2]),$children);
        }elseif(preg_match('@^#@', $matches[2] , $matchdummy ) !== 0 ) {
          $body [] = self::tag('a',array('href' => $matches[2]),$children);
        }else{
          $body [] = self::tag('a',array('href' => '/mongo/' . $matches[2]),$children);
        }
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^([^&]+)(&.*)@', $text , $matches ) !== 0 ) {
        $body = array_merge($body,$this->parse_inner($matches[1]));
        $text = $matches[2];
        next;
      }elseif ( preg_match('@^&ref\(([^\),]*)(?:,(\d*)(?:,(\d*))?)?\);(.*)@', $text , $matches ) !== 0 ) {
        // IMG => &ref(<image>,<height>,<width>);
        $attr = array();
        if ( $matches[2] ) {
          $attr['height'] = $matches[2];
        }
        if ( $matches[3] ) {
          $attr['width'] = $matches[3];
        }
        if ( preg_match('@^https?://@', $matches[1] , $matchdummy ) !== 0 ) {
          $attr['src'] = $matches[1];
          $body [] = self::tag('a',array('href' => $matches[1]),array(self::tag('img',$attr)));
        }else {
          $attr['src'] = '/mongo/img/'.$this->page.'?n='.$matches[1];
          $body [] = self::tag('a',array('href' => '/mongo/img/'.$this->page.'?n='.$matches[1]),array(self::tag('img',$attr)));
        }
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^&frame\(([^\),]*)(?:,(\d*)(?:,(\d*))?)?\);(.*)@', $text , $matches ) !== 0 ) {
        // IFRAME => &frame(<url>,<height>,<width>);
        $attr = array();
        if ( $matches[2] ) {
          $attr['height'] = $matches[2];
        }
        if ( $matches[3] ) {
          $attr['width'] = $matches[3];
        }
        $body [] = self::tag('iframe',array('src' => $matches[1]),'FRAME : ' . $matches[1]);
        $text = $matches[4];
        next;
      }elseif ( preg_match('@^&anchor\(([^\)]*)\);(.*)@', $text , $matches ) !== 0 ) {
        // ANCHOR => &anchor(<name>);
        $body [] = self::tag('a',array('href' => '#'.$matches[1], 'name' => $matches[1]),'+');
        $text = $matches[2];
        next;
      }elseif ( preg_match('@^&color\(([^\)]+)\)(.*)@', $text , $matches ) !== 0 ) {
        // COLOR => &color(<color>){<text>};
        $brackets = self::parse_brackets($matches[2]);
        $body [] = self::tag('span',array('style' => 'color:' . $matches[1]),$this->parse_inner($brackets[0]));
        $text = $brackets[1];
        next;
      }elseif ( preg_match('@^&b\(([^\)]+)\)(.*)@', $text , $matches ) !== 0 ) {
        // BOLD => &b(<level>){<text>};
        $brackets = self::parse_brackets($matches[2]);
        $body [] = self::tag('b',array('class' => 'b' . $matches[1]),$this->parse_inner($brackets[0]));
        $text = $brackets[1];
        next;
      }elseif ( preg_match('@^&del(.*)@', $text , $matches ) !== 0 ) {
        // DEL => &del{};
        $brackets = self::parse_brackets($matches[1]);
        $body [] = self::tag('del',array(),$this->parse_inner($brackets[0]));
        $text = $brackets[1];
        next;
      }elseif ( preg_match('@^&& (.*)@', $text , $matches ) !== 0 ) {
        // BR =>
        $body [] = self::tag('br');
        $text = $matches[1];
      }else{
        $body [] = self::tag('text',array(),$text);
        break;
      }
    }
    return $body;
  }
}
