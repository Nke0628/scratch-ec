<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「一覧ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// 画面表示処理
//================================

//GETパラメーターの取得/ない場合はデフォルト値を設定
$page_num = (!empty($_GET['p'])) ? h($_GET['p']) : '1';
$sort_key = (!empty($_GET['sort_key'])) ? h($_GET['sort_key']) : '1';
$category_id = (!empty($_GET['category_id'])) ? h($_GET['category_id']) : '';

//データ取得
$dbProductData = getProductsAll($page_num,$sort_key,$category_id);
$dbCategoryData = getCategory();
$total = $dbProductData['total'];
$total_page = ceil($total / 20); 
$offset = $dbProductData['offset'];
logDebug('商品情報' .print_r($dbProductData,true));
logDebug('カテゴリー情報' .print_r($dbCategoryData,true));

$siteTitle = '一覧';
require 'head.php';
require 'header.php';

?>
<!-- メインコンテンツ -->
<div class="index-wrapper">
  <div class="main site-width">
    <div class="index">
      <div class="index-left bg-gray">
      	<form method="GET" action="">
      	  <label>カテゴリー</label>
      	  <select name="category_id">
            <?php foreach ($dbCategoryData as $key => $value): ?>
               <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
            <?php endforeach ?>
      	  </select>
      	  <label>表示順</label>
      	  <select name="sort_key">
      		<option value="1" selected>新着順</option>
      		<option value="2">古い順</option>		
      		<option value="3">料金の高い順</option>
       		<option value="4">料金の高い順</option>
      	  </select>
      	  <input type="submit" name="submit" class="btn" value="検索する">
      	</form>
      </div>
      <div class="index-right">
      	<div class="index-right-header bg-gray">
      		<p><?php echo $total ?>件の商品が見つかりました。</p><p><?php echo $offset + 1;?>-<?php echo $offset+20 ;?> / <?php echo $total ?>件中</p>
      	</div>
      	<div class="index-right-grid-container">
            <?php foreach ($dbProductData['data'] as $key => $value): ?>
            <div>
              <div>
              	<img src="<?php echo showImg($value['pic1']); ?>">
              </div>
              <p>test</p>
              <p class="price">¥100</p>
            </div>
            <?php endforeach ?>
      	</div>
      	<?php pagination($page_num, $total_page); ?>
      </div>     
    </div>
  </div>
</div>
<?php
require 'footer.php';
?>

