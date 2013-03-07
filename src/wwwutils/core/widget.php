<?php
/**
 * widget.php - Component implement
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
/**
 * Widget base class
 *
 *  Wigit HTML format:
 *    <div class="co-Widget">
 *      <div class="co-Wbody">
 *        <!-- children wigit -->
 *      </div>
 *    </div>
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class  Widget {
  /**
   * Children widget
   */
  protected $children = array();
  /**
   * Layout properties
   */
  protected $prop;
  /**
   * Component properties
   */
  protected $component;
  /**
   * NORMAL / CMS / DEBUG
   */
  protected $mode;
  /**
   * HTML class
   */
  protected $clazz;
  /**
   * HTML id
   */
  protected $id;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $prop       Layout properties
   * @param String $components Component properties
   * @param String $mode       MODE
   */
  public function __construct( &$prop , &$components ,$mode) {
    $this->prop        = $prop;
    $this->component   = $components[$prop[Def::K_LAYOUT_COMPONENT]];
    $this->mode        = $mode;
    $this->id          = isset($this->component[Def::K_COMPONENT_ID])?$this->component[Def::K_COMPONENT_ID]:null;
    $this->clazz       = 'co-Widget '.$this->prop[Def::K_LAYOUT_CLASS] .' '.$this->component[Def::K_COMPONENT_CLASS];
  }

  /**
   * Construct Wigit objects with recursive
   *
   * @param String $components Component properties
   */
  public function layoutWalk(&$components){
    foreach ( $this->prop[Def::K_LAYOUT_CHILDREN] as $childProp ) {
      $child = WidgetFactory::getWidget($childProp,$components,$this->mode);
      $child->layoutWalk($components);
      $this->children[] = $child;
    }
  }

  /**
   * Collect CSS data string with recursive
   *
   * @return String Returns CSS
   */
  public function cssWalk(){
    $ret = isset($this->component[Def::K_COMPONENT_CSS])?$this->component[Def::K_COMPONENT_CSS]:'';
    foreach ( $this->children as $child ) {
      $ret .= $child->cssWalk();
    }    
    return $ret;
  }
  /**
   * Collect JS data string with recursive
   *
   * @return String Returns JS
   */
  public function jsWalk(){
    $ret = isset($this->component[Def::K_COMPONENT_JS])?$this->component[Def::K_COMPONENT_JS]:'';
    foreach ( $this->children as $child ) {
      $ret .= $child->jsWalk();
    }    
    return $ret;
  }

  /**
   * Collect action BRL list with recursive
   *
   * @return Array Returns action BRL list
   */
  public function actionWalk(){
    $ret = $this->component[Def::K_COMPONENT_ACTION];
    foreach ( $this->children as $child ) {
      $childRet = $child->actionWalk();
      if ( $childRet ) {
        $ret = array_merge($ret,$childRet);
      }
    }
    return $ret;
  }

  /**
   * Collect HEADER data string with recursive
   *
   * @return String Returns HEADER
   */
  public function headerWalk(){
    $ret = isset($this->component[Def::K_COMPONENT_HEADER])?$this->component[Def::K_COMPONENT_HEADER]:'';
    foreach ( $this->children as $child ) {
      $ret .= $child->headerWalk();
    }    
    return $ret;
  }

  /**
   * Collect BODY-BOTTOM data string with recursive
   *
   * @return String Returns BODY-BOTTOM
   */
  public function bottomWalk(){
    $ret = isset($this->component[Def::K_COMPONENT_BOTTOM])?$this->component[Def::K_COMPONENT_BOTTOM]:'';
    foreach ( $this->children as $child ) {
      $ret .= $child->bottomWalk();
    }    
    return $ret;
  }

  /**
   * Collect HTML-String with recursive
   *
   * @return String Returns HTML
   */
  public function drawWalk($clazz='',$style=''){
    if ( $style ) {
      $style = $style . ';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_HEIGHT]) and $this->prop[Def::K_LAYOUT_HEIGHT] ) {
      $style .= 'height:'.$this->prop[Def::K_LAYOUT_HEIGHT].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_WIDTH]) and $this->prop[Def::K_LAYOUT_WIDTH] ) {
      $style .= 'width:'.$this->prop[Def::K_LAYOUT_WIDTH].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_MIN_HEIGHT]) and $this->prop[Def::K_LAYOUT_MIN_HEIGHT] ) {
      $style .= 'min-height:'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_MIN_WIDTH]) and $this->prop[Def::K_LAYOUT_MIN_WIDTH] ) {
      $style .= 'min-width:'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].';';
    }

    $ret = '<div '.($this->id?'id="'.$this->id.'"':'').' class="' . $clazz . ' ' . $this->clazz .'" extra="'.$this->prop[Def::K_LAYOUT_EXTRA].($style?'" style="'.$style:'').'">' .
      '<div class="co-Wbody">' . $this->component[Def::K_COMPONENT_BODY];
    foreach ( $this->children as $child ) {
      $ret .= $child->drawWalk();
    }
    $ret .= '</div></div>';
    return $ret;
  }

  /**
   * Collect HTML-String as CMS with recursive
   *
   * @return String Returns HTML
   */
  public function cmsWalk($clazz='',$style=''){
    if ( $style ) {
      $style = $style . ';';
    }
    if ( $this->prop[Def::K_LAYOUT_HEIGHT] ) {
      $style .= 'height:'.$this->prop[Def::K_LAYOUT_HEIGHT].';';
    }
    if ( $this->prop[Def::K_LAYOUT_WIDTH] ) {
      $style .= 'width:'.$this->prop[Def::K_LAYOUT_WIDTH].';';
    }
    if ( $this->prop[Def::K_LAYOUT_MIN_HEIGHT] ) {
      $style .= 'min-height:'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].';';
    }
    if ( $this->prop[Def::K_LAYOUT_MIN_WIDTH] ) {
      $style .= 'min-width:'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].';';
    }

    $actions = '';
    foreach ( $this->component[Def::K_COMPONENT_ACTION] as $action ) {
      $actions .= $action . "\n";
    }

    $ret = '<div class="' . $clazz . ' ' . $this->clazz .'" ' . Def::K_COMPONENT_TYPE . '="' . $this->component[Def::K_COMPONENT_TYPE] . '" ' . Def::K_LAYOUT_COMPONENT . '="' . $this->prop[Def::K_LAYOUT_COMPONENT] . '" ' . Def::K_LAYOUT_EXTRA . '="'.$this->prop[Def::K_LAYOUT_EXTRA].'" style="'. $style .'">'.
      '<h3>'.$this->component[Def::K_COMPONENT_SUBJECT].'<spawn class="del">x</spawn><spawn class="down">v</spawn><spawn class="up">^</spawn></h3>'.
      '<form style="display:none;">'.
      '<div class="label">Class</div><div class="value"><input type="text" name="class" value="'.$this->prop[Def::K_LAYOUT_CLASS].'"></input></div>' .
      '<div class="label">Width</div><div class="value"><input type="text" name="width" value="'.$this->prop[Def::K_LAYOUT_WIDTH].'"></input></div>' .
      '<div class="label">Height</div><div class="value"><input type="text" name="height" value="'.$this->prop[Def::K_LAYOUT_HEIGHT].'"></input></div>' .
      '<div class="label">Min-Width</div><div class="value"><input type="text" name="min_width" value="'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].'"></input></div>' .
      '<div class="label">Min-Height</div><div class="value"><input type="text" name="min_height" value="'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].'"></input></div>' .
      '</form>'.
      '<div class="co-Wbody">';
    foreach ( $this->children as $child ) {
      $ret .= $child->cmsWalk();
    }
    $ret .= '</div></div>';
    return $ret;
  }
  
  /**
   * Collect HTML-String as template with recursive
   *
   * @return String Returns HTML
   */
  public function templateWalk(){
    return '<div class="' . $this->clazz .' co-Template" ' . Def::K_COMPONENT_TYPE . '="' . $this->component[Def::K_COMPONENT_TYPE] .'" '. Def::K_LAYOUT_COMPONENT . '="' . $this->prop[Def::K_LAYOUT_COMPONENT] . '" ' . Def::K_LAYOUT_EXTRA . '="'.$this->prop[Def::K_LAYOUT_EXTRA].'">'.
      '<h3>'.$this->component[Def::K_COMPONENT_SUBJECT].'<spawn class="del">x</spawn><spawn class="down">v</spawn><spawn class="up">^</spawn></h3>'.
      '<div class="co-Wbody">' . $this->component[Def::K_COMPONENT_DESCRIPTION] . '</div></div>';
  }

}

/**
 * Do nothing widget
 *   Same as nothing
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class GhostWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= '';
  }
  /**
   * Collect HTML-String as template with recursive
   *
   * @see Widget.php
   */
  public function templateWalk(){
    $ret = '';
    foreach ( $this->children as $child ) {
      $ret .= $child->templateWalk();
    }
    return $ret;
  }
  /**
   * Collect HTML-String as CMS with recursive
   *
   * @see Widget.php
   */
  public function cmsWalk($clazz='',$style=''){
    $ret = '';
    foreach ( $this->children as $child ) {
      $ret .= $child->cmsWalk();
    }
    return $ret;
  }
  /**
   * Collect HTML-String with recursive
   *
   * @see Widget.php
   */
  public function drawWalk($clazz='',$style=''){
    $ret = '';
    foreach ( $this->children as $child ) {
      $ret .= $child->drawWalk();
    }
    return $ret;
  }
}

/**
 * Plain contents widget
 *   Same as nothing
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class PlainWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Plain co-Fixed';
  }
  /**
   * Construct Wigit objects with recursive
   *
   * @param String $components Component properties
   */
  public function layoutWalk(&$components){
  }

  /**
   * Collect CSS data string with recursive
   *
   * @return String Returns CSS
   */
  public function cssWalk(){
    return '';
  }
  /**
   * Collect JS data string with recursive
   *
   * @return String Returns JS
   */
  public function jsWalk(){
    return '';
  }
  /**
   * Collect HTML-String with recursive
   *
   * @return String Returns HTML
   */
  public function drawWalk($clazz='',$style=''){
    return $this->component[Def::K_COMPONENT_BODY];
  }
}
/**
 * Json contents widget
 *   Same as nothing
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class JsonWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Json co-Fixed';
  }
  /**
   * Construct Wigit objects with recursive
   *
   * @param String $components Component properties
   */
  public function layoutWalk(&$components){
  }

  /**
   * Collect CSS data string with recursive
   *
   * @return String Returns CSS
   */
  public function cssWalk(){
    return '';
  }
  /**
   * Collect JS data string with recursive
   *
   * @return String Returns JS
   */
  public function jsWalk(){
    return '';
  }
  /**
   * Collect HTML-String with recursive
   *
   * @return String Returns HTML
   */
  public function drawWalk($clazz='',$style=''){
    return $this->component[Def::K_COMPONENT_BODY];
  }
}
/**
 * Binary contents widget
 *   Same as nothing
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BinaryWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Bin co-Fixed';
  }
  /**
   * Construct Wigit objects with recursive
   *
   * @param String $components Component properties
   */
  public function layoutWalk(&$components){
  }

  /**
   * Collect CSS data string with recursive
   *
   * @return String Returns CSS
   */
  public function cssWalk(){
    return '';
  }
  /**
   * Collect JS data string with recursive
   *
   * @return String Returns JS
   */
  public function jsWalk(){
    return '';
  }
  /**
   * Collect HTML-String with recursive
   *
   * @return String Returns HTML
   */
  public function drawWalk($clazz='',$style=''){
    return $this->component[Def::K_COMPONENT_BODY];
  }
}

/**
 * Marker widget
 *
 *   add class :  co-Pagelayout
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class PageLayout extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Pagelayout';
  }
}

/**
 * Horizontal
 *
 *   add class :  co-Horizontal
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class HorizontalWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Horizontal';
  }
}

/**
 * Vertical
 *
 *   add class :  co-Vertical
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class VerticalWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Vertical';
  }
  /**
   * Collect HTML-String with recursive
   *
   * @return String Returns HTML
   */
  public function drawWalk($clazz='',$style=''){
    if ( $style ) {
      $style = $style . ';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_HEIGHT]) and $this->prop[Def::K_LAYOUT_HEIGHT] ) {
      $style .= 'height:'.$this->prop[Def::K_LAYOUT_HEIGHT].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_WIDTH]) and $this->prop[Def::K_LAYOUT_WIDTH] ) {
      $style .= 'width:'.$this->prop[Def::K_LAYOUT_WIDTH].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_MIN_HEIGHT]) and $this->prop[Def::K_LAYOUT_MIN_HEIGHT] ) {
      $style .= 'min-height:'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].';';
    }
    if ( isset($this->prop[Def::K_LAYOUT_MIN_WIDTH]) and $this->prop[Def::K_LAYOUT_MIN_WIDTH] ) {
      $style .= 'min-width:'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].';';
    }

    $ret = '<div '.($this->id?'id="'.$this->id.'"':'').' class="' . $clazz . ' ' . $this->clazz .'" extra="'.$this->prop[Def::K_LAYOUT_EXTRA].($style?'" style="'.$style:'').'">' .
      '<div class="co-Wbody">' . $this->component[Def::K_COMPONENT_BODY];

    $c = count($this->children);
    if ( $c > 0 ) {
      $ret .= $this->children[0]->drawWalk('','float:'.$this->prop[Def::K_LAYOUT_VPOS].';width:'.$this->prop[Def::K_LAYOUT_SWIDTH]);
    }
    if ( $c > 1 ) {
      $ret .= $this->children[1]->drawWalk('','float:none;width:auto;margin-'.$this->prop[Def::K_LAYOUT_VPOS].':'.$this->prop[Def::K_LAYOUT_SWIDTH]);
    }
    $ret .= '</div></div>';
    return $ret;
  }
  /**
   * Collect HTML-String as CMS with recursive
   *
   * @return String Returns HTML
   */
  public function cmsWalk($clazz='',$style=''){
    if ( $style ) {
      $style = $style . ';';
    }
    if ( $this->prop[Def::K_LAYOUT_HEIGHT] ) {
      $style .= 'height:'.$this->prop[Def::K_LAYOUT_HEIGHT].';';
    }
    if ( $this->prop[Def::K_LAYOUT_WIDTH] ) {
      $style .= 'width:'.$this->prop[Def::K_LAYOUT_WIDTH].';';
    }
    if ( $this->prop[Def::K_LAYOUT_MIN_HEIGHT] ) {
      $style .= 'min-height:'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].';';
    }
    if ( $this->prop[Def::K_LAYOUT_MIN_WIDTH] ) {
      $style .= 'min-width:'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].';';
    }

    $actions = '';
    foreach ( $this->component[Def::K_COMPONENT_ACTION] as $action ) {
      $actions .= $action . "\n";
    }
    $ret = '<div class="' . $clazz . ' ' . $this->clazz .'" ' . Def::K_COMPONENT_TYPE . '="' . $this->component[Def::K_COMPONENT_TYPE] . '" ' . Def::K_LAYOUT_COMPONENT . '="' . $this->prop[Def::K_LAYOUT_COMPONENT] . '" ' . Def::K_LAYOUT_EXTRA . '="'.$this->prop[Def::K_LAYOUT_EXTRA].'" style="'. $style .'">'.
      '<h3>'.$this->component[Def::K_COMPONENT_SUBJECT].'<spawn class="del">x</spawn><spawn class="down">v</spawn><spawn class="up">^</spawn></h3>'.
      '<form style="display:none;">'.
      '<div class="label">Class</div><div class="value"><input type="text" name="class" value="'.$this->prop[Def::K_LAYOUT_CLASS].'"></input></div>' .
      '<div class="label">Width</div><div class="value"><input type="text" name="width" value="'.$this->prop[Def::K_LAYOUT_WIDTH].'"></input></div>' .
      '<div class="label">Height</div><div class="value"><input type="text" name="height" value="'.$this->prop[Def::K_LAYOUT_HEIGHT].'"></input></div>' .
      '<div class="label">Min-Width</div><div class="value"><input type="text" name="min_width" value="'.$this->prop[Def::K_LAYOUT_MIN_WIDTH].'"></input></div>' .
      '<div class="label">Min-Height</div><div class="value"><input type="text" name="min_height" value="'.$this->prop[Def::K_LAYOUT_MIN_HEIGHT].'"></input></div>' .

      '<div class="label">SideWidth</div><div class="value"><input type="text" name="swidth" value="'.$this->prop[Def::K_LAYOUT_SWIDTH].'"></input></div>' .
      '<div class="label">SidePos</div><div class="value"><select name="vpos" value="'.$this->prop[Def::K_LAYOUT_VPOS].'">'.
        '<option value="left" '.(($this->prop[Def::K_LAYOUT_VPOS]=="left")?"selected":"").'>left</option>'.
        '<option value="right" '.(($this->prop[Def::K_LAYOUT_VPOS]=="right")?"selected":"").'>right</option>'.
      '</select></div>' .

      '</form>'.
      '<div class="co-Wbody">';
    $c = count($this->children);
    $i = 0;
    foreach ( $this->children as $child ) {
      $i++;
      if ( $i === $c ) {
        // last
        $ret .= $child->cmsWalk('co-VMain','float:none;width:auto;margin-'.$this->prop[Def::K_LAYOUT_VPOS].':'.$this->prop[Def::K_LAYOUT_SWIDTH]);
      }else {
        $ret .= $child->cmsWalk('co-VSub','float:'.$this->prop[Def::K_LAYOUT_VPOS]);
      }
    }
    $ret .= '<br clear="both"/></div></div>';
    return $ret;
  }
}


/**
 * Random
 *
 *   add class :  co-Random
 *   Drow only one child-widget.
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class RandomWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Random';
  }
  /**
   * Construct Wigit objects with recursive
   *
   *  Choise a child
   *
   * @see Wigit.php
   */
  public function layoutWalk(&$components){
    if ( $this->mode & Def::RenderingModeCMS ) {
      parent::layoutWalk($components);
    }elseif ( $this->mode & Def::RenderingModeCMSTEMPLATE ) {
    }else{
      $random = rand(1,10000);
      $sum = 0;
      foreach ( $this->prop[Def::K_LAYOUT_CHILDREN] as $childProp ) {
        $sum += $childProp[Def::K_LAYOUT_EXTRA];
      }
      $random*=$sum/10000;
      foreach ( $this->prop[Def::K_LAYOUT_CHILDREN] as $childProp ) {
        $random -= $childProp[Def::K_LAYOUT_EXTRA];
        if ( $random <= 0 ) {
          $child = WidgetFactory::getWidget($childProp,$components,$this->mode);
          $child->layoutWalk($components);
          $this->children[] = $child;
          break;
        }
      }      
    }
  }
}

/**
 * Tab
 *
 *   add class :  co-Tab
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class TabWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Tab';
  }
}
/**
 * Tab element
 *
 *   add class :  co-TabChild
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class TabChildWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-TabChild';
    if ( strcmp($this->prop[Def::K_LAYOUT_EXTRA],'selected') === 0 ) {
      $this->clazz .= " selected";
    }
  }
}

/**
 * Tile
 *
 *   add class :  co-Tile
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @see Widget.php
 */
class TileWidget extends Widget {
  /**
   * Constructor
   *
   * @see Widget.php
   */
  public function __construct( &$prop,&$cprops,$mode ) {
    parent::__construct( $prop,$cprops,$mode);
    $this->clazz .= ' co-Tile';
  }
}

/**
 * Creater widget by properties
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class WidgetFactory {
  /**
   * Creater widget by properties
   *
   * @param Array $prop   Layout properties
   * @param Array $cprops Component properties
   * @param Array $mode   MODE
   */
  static public function getWidget(&$prop,&$cprops,$mode){
    $type = $cprops[$prop[Def::K_LAYOUT_COMPONENT]][Def::K_COMPONENT_TYPE];
    if ( $type ) {
      $type = 'Cockatoo\\' . $type;
    }else{
      $type = 'Cockatoo\\GhostWidget';
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Fail to get compoenent => ' . $prop[Def::K_LAYOUT_COMPONENT]);
    }
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $type . ' , ' . $prop[Def::K_LAYOUT_COMPONENT]);
    return new $type($prop,$cprops,$mode);
  }
}

