<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * ExamAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class ExamAction extends \Cockatoo\Action {
  private static $EREDIRECT = '/mongo/exams';

  public function proc(){
    static $NUM_QUESTION  = 10;
    static $NUM_CANDIDATE = 4;
    try{
      // 
      $this->setNamespace('mongo');
      $session = $this->getSession();
      $post = $session[\Cockatoo\Def::SESSION_KEY_POST];
      $user  = Lib::user($session);
      $examid   = $this->args['E'];

      if ( $this->method === \Cockatoo\Beak::M_GET ) {
        $op = $post['op'];
        if ( $op === 'eval' ) {
          $data = $session['exam'];
          $qs =& $data['qs'];
          
          $all = sizeof($qs);
          $correct = 0;
          array_walk($qs,function(&$e,$i) use ($session,$post,$all,&$correct){
              if ( $post['q'.$i.'a'] !== null && (int)$post['q'.$i.'a'] === (int)$e['correct'] ) {
                $correct++;
              }
              $e['checked'] = $post['q'.$i.'a'];
              $e['show'] = 'show';
            });
          $data['score'] = floor(100*$correct/$all);
          $data['done'] = '1';
          $s['exam'] = null;
          $this->updateSession($s);
          return array( 'exam' => $data);
        }else{
          $data = Lib::get_exam($session,$examid);
          if ( $data ) {
            $qs = array_filter($data['qs'],function ($e) {
                return (boolean)$e['show'];
              });
            array_walk($qs,function(&$e){
                $e['show'] = 'q';
                shuffle($e['s']);
                foreach ( $e['s'] as $k => $v ) {
                  if ( $v === $e['a'] ) {
                    $e['correct'] = $k;
                    break;
                  }
                }
              });
            shuffle($qs);
            $qs = array_slice($qs,0,$data['qnum']);
            $qs[sizeof($qs)-1]['last'] = 1;
            $data['qs'] = $qs;
            $s['exam'] = $data;
            $this->updateSession($s);
            return array( 'exam' => $data);
          }
        }
        $this->setMovedTemporary(self::$EREDIRECT);
        return null;
      }elseif( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
        $datas = Lib::get_exams($session);
        return array('exams' => $datas);
      }elseif( $this->method === \Cockatoo\Beak::M_SET ) {
        if ( ! Lib::isWritable($session) ) {
          throw new \Exception('You do not have write permission.');
        }
        $op = $post['op'];
        if ( ! $op ) {
          $data = Lib::get_exam($session,$examid);
          if ( $data ) {
            $data['done'] = '1';
            return array( 'exam' => $data);
          }
          $qname = 'new';
          $qs = null;
          for( $i = 0 ; $i < $NUM_QUESTION ; $i++ ) {
            $qs []= array(
              'q' => '',
              'contents' => array(),
              'a' => '',
              'e' => '',
              'c' => array('','','',''));
          }
          return array( 'exam' => array(
                          'done' => 1,
                          'examid' => 'new',
                          'public' => '',
                          'qname' => $qname,
                          'qnum' => 5,
                          'qs' => $qs,
                          'owner' => $user
                          ));
        }
        $public  = $post['public'];
        $qname   = $post['qname'];
        $qnum    = $post['qnum'];
        $qsummary= $post['qsummary'];
        $examid  = $post['examid'];
        $owner   = $post['owner'];
        $qs = null;
        for( $i = 0 ; $i < $NUM_QUESTION ; $i++ ) {
          $origin   = $post['q'.$i];
          $lines    = preg_split("@\r?\n@",$origin);
          $parser   = new PageParser($qname,$lines);
          $contents =  $parser->parse();
          $answer   = $post['q'.$i.'a'];
          $eorigin   = $post['q'.$i.'e'];
          $lines    = preg_split("@\r?\n@",$eorigin);
          $parser   = new PageParser($qname,$lines);
          $explanation =  $parser->parse();
          $candidates = null;
          for( $c = 0 ; $c < $NUM_CANDIDATE ; $c++ ) {
            $candidates []= $post['q'.$i.'c'.$c];
          }
          $qs [] = array(
            'show' => ($answer?'show':''),
            'q' => $origin,
            'contents' => $contents,
            'a' => $answer,
            'c' => $candidates,
            'e' => $eorigin,
            'explanation' => $explanation,
            's' => array_merge(array($answer),$candidates)
            );
        }
        $data = array(
          'public' => $public,
          'examid' => $examid,
          'qname' => $qname,
          'qnum' => $qnum,
          'qsummary' => $qsummary,
          'qs' => $qs,
          'owner' => $owner);

        if( $op === 'preview' ) {
          $data['done'] = '1';
          return array( 'exam' => $data);
        }elseif( $op === 'save' ) {
          $prev_examid = $examid;
          $examid = uniqid();
          $data['examid'] = $examid;
          Lib::save_exam($examid,$data);
          Lib::remove_exam($prev_examid);
          $this->setMovedTemporary('/mongo/exams/'.$examid);
          return array();
        }
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary(self::$EREDIRECT);
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

