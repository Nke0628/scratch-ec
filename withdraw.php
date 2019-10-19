<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「退会画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

$siteTitle = '退会';
require 'head.php';
require 'header.php';

/*========================================
画面処理
========================================*/
if(!empty($_POST)){
  try{
    $db = dbConnect();
    $sql1 = 'UPDATE users SET delete_flag = 1 WHERE id = :user_id';
    $sql2 = 'UPDATE products SET delete_flag = 1 WHERE id = :user_id';
    $sql3 = 'UPDATE likes SET delete_flag = 1 WHERE id = :user_id';
    $data = array(':user_id' => $_SESSION['user_id']);
    $stmt1 = queryPost($db, $sql1, $data);
    $stmt2 = queryPost($db, $sql2, $data);
    $stmt3 = queryPost($db, $sql3, $data);

    if($stmt1){
      debug('退会しました');
      session_destroy();
      header('Location: login.php');
    }
  }catch(Exception $e){
    debug('エラーが発生しました'. $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}

?>
<!-- メインコンテンツ -->
<div class="contents-wrapper mt50">
  <div class="main site-width" >
    <div class="form-container bd-gold">
      <form action="" method="post" class="form">
        <h2 class="title">退会</h2>
        <div class="area-msg">
          <?php
          	if(!empty($err_msg['common'])){
          	  echo $err_msg['common'];
          	}
          ?>
        </div>
        <div class="subsc-btn-container">
          <input type="submit" class="btn" value="退会します" name="submit">
        </div>
      </form>
    </div>
  </div>
</div>
<?php
require 'footer.php';
?>

