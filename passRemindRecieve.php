<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「パスワードリマインド認証画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// 画面処理
//================================
//セッション確認
if(empty($_SESSION['auth_key'])){
    header('Loaction: passRemindSend.php');
    exit;
}

//POST処理
if(!empty($_POST)){
    debug('POST送信されました');
    debug('POST情報' . print_r($_POST,true));

    //変数格納
    $auth_key = h($_POST['auth_key']);

    //バリデーション
    validRequired($auth_key,'auth_key');

    if(empty($err_msg)){
        //authkeyの形式チェック
        validMaxLen($auth_key,'auth_key',8);

        if($_SESSION['auth_key'] !== $auth_key){
            $err_msg = MSG11;
        }

        if($_SESSION['auth_limit'] < time()){
            $err_msg = MSG12;
        }

        if(empty($err_msg)){
            debug('バリデーションOK');
            try{
                $pass = makeAuthKey();
                $db = dbConnect();
                $sql = 'UPDATE users SET password = :password WHERE email = :email AND delete_flag = 0';
                $data = array(
                    ':email' => $_SESSION['auth_email'], 
                    ':password' => password_hash($pass,PASSWORD_DEFAULT),
                ); 
                $stmt = queryPost($db,$sql,$data);
                //クエリ成功かつデータ取得できた場合
                if($stmt){
                    //メール送信
                    $from = 'info@photomarket.com';
                    $to = $_SESSION['auth_email'];
                    $subject = 'パスワード変更 | PHOTOMARKET';
                    $message = <<<EOF
本メールアドレスのパスワードを再発行しました。

ログインページ:https://www.erroriscode.online/scratch_php_web/login.php
新しいパスワード:{$pass}

ログインページで、ログインして下さい。
ログイン後は新しいパスワードに変更していただきますようお願い致します。

////////////////////////////////////////////////////
フォトマーケット
TEL:080-6128-6075
info@photomarket.com
////////////////////////////////////////////////////
EOF;

                    sendMail($from, $to, $subject, $message);
                    //セッション格納
                    session_unset();
                    $_SESSION['msg_success'] = SUC01;
                    debug('セッション変数: ' .print_r($_SESSION,true));
                    //遷移
                    header('Location: login.php');
                    exit;
                }else{
                    debug('メール送信失敗しました');
                    $err_msg['pass'] = MSG10;
                }
            }catch(Exception $e){
                error_log('エラー発生' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}

$siteTitle = 'パスワード再発行(認証)';
require 'head.php';
require 'header.php';

?>
<div class="contents-wrapper mt50">
  <section id="main site-width">
    <div class="form-container bd-gold">
      <form action="" method="post" class="form">
        <h2 class="title">パスワード再発行(認証)</h2>
        <div class="area-msg">
          <?php
              if(!empty($err_msg['common'])){
                  echo $err_msg['common'];
              }
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['auth_key'])) echo 'err' ;?>">
          認証キーを入力してください。
          新しいパスワードでログイン後は、パスワードを任意のものに変更してください。
        <input class="" type="text" name="auth_key" value="<?php if(!empty($_POST['auth_key'])) echo h($_POST['auth_key']);?>">
        </label>
        <div class="area-msg tx-red mb15">
          <?php
              if(!empty($err_msg['auth_key'])){
                  echo $err_msg['auth_key'];
              }
          ?>
        </div>
        <div class="btn-container mt20">
          <input type="submit" class="fl-right btn" value="送信する" name="submit">
        </div>
      </form>
    </div>
  </section>
</div>
<?php
require 'footer.php';
?>

