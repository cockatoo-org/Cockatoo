<?php
/**
 * ClassLoader.php - Class loader
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

/**
 * ClassLoader (Singleton)
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class ClassLoader {
  /**
   * Singleton
   */
  static $instance;

  /**
   * Search path
   */
  protected $CLASS_PATH=array();

  /**
   * Add search path
   *
   * @param String $classpath path
   */
  public static function addClassPath($classpath) {
    if ( ! self::$instance ) {
      self::$instance = new ClassLoader();
      spl_autoload_register(array(self::$instance, 'autoload'));
    }
    $classpath = rtrim($classpath,DIRECTORY_SEPARATOR);
    if ( ! in_array($classpath,self::$instance->CLASS_PATH) ) {
      self::$instance->CLASS_PATH []= $classpath;
    }
  }

  /**
   * Require file
   *
   * @param String $file filepath
   */
  private function load ($file){
    if ( is_file($file) ) {
      return require_once($file);
    }
  }

  /**
   * SPL autoload handler
   * 
   * @param String $clazz class-name
   */
  public function autoload($clazz) {
    $file = str_replace('\\',DIRECTORY_SEPARATOR,$clazz);
    foreach(self::$instance->CLASS_PATH as $PATH ) {
      if ( $this->load($PATH . DIRECTORY_SEPARATOR . $file . '.php') ) {
        return;
        break;
      }
    }
    throw new \Exception('Load Fail : ' . $PATH . DIRECTORY_SEPARATOR . $file . '.php<br>');
  }
}
