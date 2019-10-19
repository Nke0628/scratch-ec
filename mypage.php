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
<!-- メインコンテンツ -->
<div class="contents-wrapper mt25">
  <div class="main site-width">
    <h2 class="mypage-title text-center">MYPAGE</h2>
    <div class="mypage">
      <div class="mypage-info">
      </div>
      <div class="mypage-menu bg-gray">
        <ul>
          <li><a class="tx-gold" href="">商品を出品する</a></li>
          <li class="mt5"><a class="tx-gold" href="">販売履歴</a></li>
          <li class="mt5"><a class="tx-gold" href="">プロフィール編集</a></li>
          <li class="mt5"><a class="tx-gold" href="">パスワード変更</a></li>
          <li class="mt5"><a class="tx-gold" href="withdraw.php">退会する</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php
require 'footer.php';
?>

