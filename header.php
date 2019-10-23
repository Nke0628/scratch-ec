  	<header>
  		<div class="menu site-width">
  			<h2><a href="index.php">PHOTOMRKEAT</a></h2>
  			<ul>
  			<?php 
  				if(empty($_SESSION['login_time'])){
  			?>
  				<li><a href="login.php">ログイン</a></li>
  				<li><a href="signup.php" class="btn">ユーザ登録</a></li>
  			<?php
  			}else{
  			?>
  				<li><a href="logout.php">ログアウト</a></li>
  				<li><a href="mypage.php">マイページ</a></li>
  			<?php
  			}
  			?>
  			</ul>
  		</div>
  	</header>