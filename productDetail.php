<?php

//共通関数読み込み
require 'function.php';
logDebug('商品詳細ページ');

//================================
// 画面表示処理
//================================

//GETパラメータの格納(からであれば一覧ページに遷移)
$product_id = (!empty($_GET['product_id'])) ? h($_GET['product_id']) : '';
if(empty($product_id)){
  logWarning('商品IDが不正です。一覧ページに遷移します');
  header('Location: index.php');
  exit;
}
//商品情報取得(データ取得できなければ一覧ページに遷移)
$dbProductData = getProductDetail($product_id);
if(empty($dbProductData)){
  logWarning('商品情報が取得できません。一覧ページに遷移します');
  header('Location: index.php');
  exit;
}

$siteTitle = '商品詳細ページ';
require 'head.php';
require 'header.php';

?>
  <div class="main site-width">

    <div class="mask">
      <div class="modal">
        <div class="close">
          <i class="fas fa-times"></i>
        </div>
        <div class="slider">
          <ul>
            <i class="fas fa-angle-left slider-left"></i>
            <li><img class="slider-main-img" src=""></li>
            <i class="fas fa-angle-right slider-right"></i>
          </ul>
        </div>
      </div>
    </div>

  		<div class="product-title">
  			<span class="product-title-category"><?php echo h($dbProductData['category_name']); ?></span>
  			<span class="product-title-title"><?php echo h($dbProductData['name']); ?></span>
  		</div>
  		<div class="product-img-container">
  			<div class="img-main">
  				<img src="<?php echo showImg(h($dbProductData['pic1'])); ?>">
  			</div>
  			<div class="img-sub">
   				<img src="<?php echo showImg(h($dbProductData['pic1'])); ?>">
  				<img src="<?php echo showImg(h($dbProductData['pic2'])); ?>">
  				<img src="<?php echo showImg(h($dbProductData['pic3'])); ?>">
  			</div>
  		</div>

  		<div class="product-detail">
  			<?php echo h($dbProductData['comment']); ?>
  		</div>

  		<div class="product-buy">
  			<p class="back"><a href="index.php">一覧に戻る</a></p>
  			<p class="price">¥<?php echo number_format(h($dbProductData['price'])); ?>-円</p>
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