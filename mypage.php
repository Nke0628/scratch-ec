<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

$siteTitle = 'マイページ';
require 'head.php';
require 'header.php';

?>
<!--セッションメッセージ-->
<p class="js-session-message bg-success text-center tx-black">
  <?php echo getSessionMessage('msg_success');?>
</p>
<!-- メインコンテンツ -->
<div class="contents-wrapper mt25">
  <div class="main site-width">
    <h2 class="mypage-title text-center">MYPAGE</h2>
    <div class="mypage">
      <div class="mypage-left">
      </div>
      <?php require 'sidebar.php' ;?>
    </div>
  </div>
</div>
<?php
require 'footer.php';
?>

