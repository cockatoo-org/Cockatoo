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
          $edata = $session['exam'];
          $qs =& $edata['qs'];
          
          $all = sizeof($qs);
          $correct = 0;
          array_walk($qs,function(&$e,$i) use ($session,$post,$all,&$correct){
              if ( $post['q'.$i.'a'] !== null && (int)$post['q'.$i.'a'] === (int)$e['correct'] ) {
                $correct++;
              }
              $e['checked'] = $post['q'.$i.'a'];
              $e['show'] = 'show';
            });
          $edata['score'] = floor(100*$correct/$all);
          $edata['done'] = '1';
          $s['exam'] = null;
          $this->updateSession($s);
          return array( 'exam' => $edata);
        }else{
          $edata = Lib::get_exam($examid);
          if ( $edata ) {
            $qs = array_filter($edata['qs'],function ($e) {
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
            $qs = array_slice($qs,0,$edata['qnum']);
            $qs[sizeof($qs)-1]['last'] = 1;
            $edata['qs'] = $qs;
            $s['exam'] = $edata;
            $this->updateSession($s);
            return array( 'exam' => $edata);
          }
        }
        return null;
      }elseif( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
        $exams = Lib::get_exams();
        return array('exams' => $exams);
      }elseif( $this->method === \Cockatoo\Beak::M_SET ) {
        Lib::isWritable($session);
        $op = $post['op'];
        if ( ! $op ) {
          $edata = Lib::get_exam($examid);
          if ( $edata ) {
            $edata['done'] = '1';
            return array( 'exam' => $edata);
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
        $public = $post['public'];
        $qname   = $post['qname'];
        $qnum    = $post['qnum'];
        $qsummary= $post['qsummary'];
        $examid  = $post['examid'];
        $qs = null;
        for( $i = 0 ; $i < $NUM_QUESTION ; $i++ ) {
          $origin   = $post['q'.$i];
          $lines    = explode("\n",$origin);
          $parser   = new PageParser($qname,$lines);
          $contents =  $parser->parse();
          $answer   = $post['q'.$i.'a'];
          $eorigin   = $post['q'.$i.'e'];
          $lines    = explode("\n",$eorigin);
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
        $edata = array(
          'public' => $public,
          'examid' => $examid,
          'qname' => $qname,
          'qnum' => $qnum,
          'qsummary' => $qsummary,
          'qs' => $qs,
          'owner' => $user);

        if( $op === 'preview' ) {
          $edata['done'] = '1';
          return array( 'exam' => $edata);
        }elseif( $op === 'save' ) {
          $prev_examid = $examid;
          $examid = uniqid();
          $edata['examid'] = $examid;
          Lib::save_exam($examid,$edata);
          Lib::remove_exam($prev_examid);
          $this->setMovedTemporary('/mongo/exams/'.$examid);
          return array();
        }
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary('/mongo/exams');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

