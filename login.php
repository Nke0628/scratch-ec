<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「ログイン画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

//================================
// ログイン画面処理
//================================
if(!empty($_POST)){
    debug('POST送信されました');
    debug('POST情報' . print_r($_POST,true));

    //変数格納
    $email = h($_POST['email']);
    $pass = h($_POST['pass']);
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    //バリデーション
    validRequired($email,'email');
    validRequired($pass,'pass');

    if(empty($err_msg)){
        //emailの形式チェック
        validEmail($email,'email');
        validMaxLen($email,'email');
        //パスワードの形式チェック
        validHalf($pass,'pass');
        validMinLen($pass,'pass');
        validMaxLen($pass,'pass');

        if(empty($err_msg)){
            debug('バリデーションOK');
            try{
                $db = dbConnect();
                $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flag = 0';
                $data = array(':email' => $email); 
                $stmt = queryPost($db,$sql,$data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!empty($result) && password_verify($pass,array_shift($result))){
                    debug('マッチしました');
                    //セッション格納
                    $ses_limit = 60 * 60;
                    //パスワード保存にチェックがある場合は、１ヶ月保存する
                    if($pass_save){
                      $_SESSION['login_limit'] = $ses_limit * 24 * 30;
                    }else{
                      $_SESSION['login_limit'] = $ses_limit;                  
                    }
                    $_SESSION['login_time'] = time();
                    $_SESSION['user_id'] = $result['id'];
                    debug('セッション変数: ' .print_r($_SESSION,true));
                    //遷移
                    header('Location: mypage.php');
                    exit;
                }else{
                    debug('ログイン失敗しました');
                    $err_msg['pass'] = MSG09;
                }
            }catch(Exception $e){
                error_log('エラー発生' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}

$siteTitle = 'ログイン';
require 'head.php';
require 'header.php';

?>
<div class="contents-wrapper mt50">
  <section id="main site-width">
    <div class="form-container bd-gold">
      <form action="" method="post" class="form">
        <h2 class="title">ログイン</h2>
        <div class="area-msg">
        </div>
        <label class="<?php if(!empty($err_msg['email'])) echo 'err' ;?>">
          Email
        <input class="mb20" type="text" name="email" value="">
        </label>
        <div class="area-msg tx-red mb15">
          <?php
              if(!empty($err_msg['email'])){
                  echo $err_msg['email'];
              }
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass'])) echo 'err' ;?>">
          パスワード <span style="font-size:12px">※英数字６文字以上</span>
        <input class="mb20" type="password" name="pass" value="">
        </label>
        <div class="area-msg tx-red mb15">
          <?php
              if(!empty($err_msg['pass'])){
                  echo $err_msg['pass'];
              }
          ?>
        </div>
        <p><input type="checkbox" name="pass_save" value="1">次回ログイン時にパスワードを省略する</p>
        <div class="btn-container mt20">
          <input type="submit" class="login-btn btn btn-mid" value="ログイン" name="submit">
        </div>
      </form>
      <p>パスワードを忘れた方は<a href="passRemindSend.php" class="tx-gold tx-under">こちら</a></p>
    </div>
  </section>
</div>
<?php
require 'footer.php';
?>

