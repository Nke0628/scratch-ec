<?php

$siteTitle = '商品詳細';
require 'head.php';
require 'header.php';

?>
  	<div class="main site-width">
  		<div class="product-title">
  			<span class="product-title-category">自転車</span>
  			<span class="product-title-title">テスト商品</span>
  		</div>

  		<div class="product-img-container">
  			<div class="img-main">
  				<img src="img/ea099a9d236108ecd1d09712911435677ac8b0a5.jpeg">
  			</div>
  			<div class="img-sub">
   				<img src="img/ea099a9d236108ecd1d09712911435677ac8b0a5.jpeg">
  				<img src="img/ea099a9d236108ecd1d09712911435677ac8b0a5.jpeg">
  				<img src="img/ea099a9d236108ecd1d09712911435677ac8b0a5.jpeg">
  			</div>
  		</div>

  		<div class="product-detail">
  			ここにテキストが入ります。
  		</div>

  		<div class="product-buy">
  			<p class="back"><a href="">一覧に戻る</a></p>
  			<p class="price">¥333-円</p>
  			<div class="buy">
  				<form method="post" action="">
  					<button  class="btn" type="submit" name="submit">買う！</button>
  				</form>
  			</div>
  		</div>
  	</div>
<?php
require 'footer.php';
?>