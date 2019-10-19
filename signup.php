<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「登録画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

if(!empty($_POST)){
  debug('POST送信されました');
  debug('POST情報' . print_r($_POST,true));

  //変数格納
	$email = h($_POST['email']);
  $pass = h($_POST['pass']);
  $pass_re = h($_POST['pass_re']);

  //バリデーション
	validRequired($email,'email');
  validRequired($pass,'pass');
  validRequired($pass_re,'pass_re'); 

  if(empty($err_msg)){
    //emailの形式チェック
    validEmailDup($email,'email');
    validEmail($email,'email');
    validMaxLen($email,'email');
    //パスワードの形式チェック
    validHalf($pass,'pass');
    validMinLen($pass,'pass');
    validMaxLen($pass,'pass');

    if(empty($err_msg)){
      validMatch($pass, $pass_re,'pass_re');

      if(empty($err_msg)){
        debug('バリデーションOK');
        try{
          $db = dbConnect();
          $sql = 'INSERT INTO users(email,password,create_date) VALUES(:email,:password,:create_date)';
          $data = array(
            ':email' => $email,
            ':password' => password_hash($pass,PASSWORD_DEFAULT),
            ':create_date' => date('Y-m-d H:i:s'),
          ); 
          $stmt = queryPost($db,$sql,$data);
          if($stmt){
            debug('登録しました');
            debug('マイページへ遷移します');
            //セッション格納
            $ses_limit = 60 * 60;
            $_SESSION['login_time'] = time();
            $_SESSION['login_limit'] = $ses_limit;
            $_SESSION['user_id'] = $db->lastInsertId();
            debug('セッション変数: ' .print_r($_SESSION,true));
            //遷移
            header('Location: mypage.php');
            exit;
          }else{
            debug('登録失敗しました');
            $err_msg['common'] = MSG07;
          }
        }catch(Exception $e){
          error_log('エラー発生' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
  }
}

$siteTitle = '商品詳細';
require 'head.php';
require 'header.php';

?>
<!-- メインコンテンツ -->
<div class="contents-wrapper mt50">
  <div class="main site-width">
      <div class="form-container bd-gold">
        <form action="" method="post" class="form">
          <h2 class="title">ユーザー登録</h2>
          <div class="area-msg">
          </div>
          <label class="<?php if(!empty($err_msg['email'])) echo 'err' ;?>">
            Email
            <input class="" type="text" name="email" value="">
          </label>
          <div class="area-msg tx-red">
          	<?php
          	    if(!empty($err_msg['email'])){
          	    	echo $err_msg['email'];
          	    }
          	?>
          </div>
          <label class="<?php if(!empty($err_msg['pass'])) echo 'err' ;?> mt15">
            パスワード <span style="font-size:12px">※英数字６文字以上</span>
            <input type="password" name="pass" value="">
          </label>
          <div class="area-msg tx-red">
            <?php
                if(!empty($err_msg['pass'])){
                  echo $err_msg['pass'];
                }
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err' ;?> mt15">
            パスワード（再入力）
            <input type="password" name="pass_re" value="">
          </label>
          <div class="area-msg tx-red">
            <?php
                if(!empty($err_msg['pass_re'])){
                  echo $err_msg['pass_re'];
                }
            ?>
          </div>
          <div class="btn-container mt15">
            <input type="submit" class="btn btn-mid" value="登録する" name="submit">
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
require 'footer.php';
?>

