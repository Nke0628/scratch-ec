<?php


$siteTitle = '商品詳細';

?>

<!DOCTYPE html>
  <html lang="ja">

  <head>
  <meta charset="utf-8">
  <title><?php echo $siteTitle; ?> | WEBUKATU MARKET</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <!--フォントアイコン-->
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  </head>
  <body>
  	<header>
  		<div class="menu site-width">
  			<h2>PHOTOMRKEAT</h2>
  			<ul>
  				<li><a href="">ログイン</a></li>
  				<li><a href="" class="btn">ユーザ登録</a></li>
  			</ul>
  		</div>
  	</header>
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
  	<footer>
  		Copyright ウェブカツ!!WEBサービス部. All Rights Reserved.
  	</footer>
  <script src="js/vendor/jquery-2.2.2.min.js"></script>
  </body>
 </html>