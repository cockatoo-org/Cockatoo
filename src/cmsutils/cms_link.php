<?php
/**
 * cms_link.php - CMS
 *  
 * @access public
 * @package cockatoo-cms
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
  namespace Cockatoo;
  require_once(Config::COCKATOO_ROOT.'cmsutils/cms_acl.php');
?>
<!-- <div style="float:right;">Welcome <b style="color:#0000FF"> -->
<style>
#identity {
  min-width:100px;
  text-align: center;
  margin: 2px 8px;
  position: absolute;
  right:50px;
  float:right;
  z-index: 1000;
}
#identity a {
  color: #AA814D;
  cursor: pointer;
}
#identity > div{
  margin: 5px 0 0 0;
  padding: 3px;
  border-style: solid;
  border-width: 1px;
  background-color: #cccccc;
  opacity: 0.5;
  display: none;
}
#identity:hover > div{
  display: block;
}
#identity > div > form {
  display:none;
}
--> </style>
<?php
  $account =  get_account();
//  print $account?$account:'Guest';
//  $login_page =  get_loginpage();
//  if ($login_page){
//    print '<br><a href="'.$login_page.'">login page</a>';
//  }
  if ( ! $account ){
    print '<div id="identity"><a href="/core/login?r=/_cms_/cms_page.php">login</a></div>';
  }else{
    print '<div id="identity"><a user="'.$account.'">'.$account.'</a>'
      . ' <div class="logout">'
      . '  <a class="logout">logout</a>'
      . '  <form method="post" action="/core/profile">'
      . '   <input type="hidden" name="r" value="/_cms_/cms_page.php"/>'
      . '   <input type="submit" name="submit" value="logout" />'
      . '  </form>'
      . ' </div>'
      . ' <div class="admin">'
      . '  <a href="/core/admin">admin tool</a>'
      . ' </div>'
      . ' <div class="profile">'
      . '  <a href="/core/profile">edit profile</a>'
      . ' </div>'
      . '</div>';
  }
?>
<script>
$( function (){
  $('#identity div.logout a.logout').click(function(ev){
    $(this).next('form').find('input[type="submit"]').click();
  });
})
</script>
<!-- </b></div> -->
<div id="header">
  <h1>Cockatoo - page editor β</h1>
  <div id="shortcut-help">
    <a>shortcut help</a>
  </div>
  <br clear="both"/>
  <div id="header-main">
    <ul>
      <li><a id="Page"      href="cms_page.php">Page</a></li>
      <li><a id="Component" href="cms_component.php">Component</a></li>
      <li><a id="Layout"    href="cms_layoutwidget.php">Layout</a></li>
      <li><a id="Static"    href="cms_static.php">Static contents</a></li>
      <li><a id="Admin"     href="cms_admin.php">Admin</a></li>
    </ul>
  </div>   
</div>
