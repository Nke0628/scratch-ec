<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「パスワードリマインド画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// 画面処理
//================================
if(!empty($_POST)){
    debug('POST送信されました');
    debug('POST情報' . print_r($_POST,true));

    //変数格納
    $email = h($_POST['email']);

    //バリデーション
    validRequired($email,'email');

    if(empty($err_msg)){
        //emailの形式チェック
        validEmail($email,'email');
        validMaxLen($email,'email');

        if(empty($err_msg)){
            debug('バリデーションOK');
            try{
                $db = dbConnect();
                $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flag = 0';
                $data = array(':email' => $email); 
                $stmt = queryPost($db,$sql,$data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                //クエリ成功かつデータ取得できた場合
                if($stmt && array_shift($result)){
                    //セッション格納
                    $_SESSION['msg_success'] = SUC01;
                    //認証キーの作成
                    $authentication_key = makeAuthKey();
                    //メール送信
                    $from = 'info@photomarket.com';
                    $to = $email;
                    $subject = 'パスワード再発行 | PHOTOMARKET';
                    $message = <<<EOF
本メールアドレス宛にパスワード再発行の依頼がありました。
下記URLにて認証キーご入力頂くと、パスワードが再発行されます。

パスワード再発行認証入力ページ:https://www.erroriscode.online/scratch_php_web/passRemindReceive.php
認証キー:{$authentication_key}
※認証キーの有効期限は30分になります。

認証キーを再発行されたい場合は下記ページから再度、再発行をして頂きますようお願い致します。
https://www.erroriscode.online/scratch_php_web/passRemindSend.php

////////////////////////////////////////////////////
フォトマーケット
TEL:080-6128-6075
info@photomarket.com
////////////////////////////////////////////////////
EOF;

                    sendMail($from, $to, $subject, $message);
                    $_SESSION['auth_key'] = $authentication_key;
                    $_SESSION['auth_email'] = $email;
                    $_SESSION['auth_limit'] = time() + (60 * 30);
                    debug('セッション変数: ' .print_r($_SESSION,true));
                    //遷移
                    header('Location: passRemindRecieve.php');
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

$siteTitle = 'ログイン';
require 'head.php';
require 'header.php';

?>
<div class="contents-wrapper mt50">
  <section id="main site-width">
    <div class="form-container bd-gold">
      <form action="" method="post" class="form">
        <h2 class="title">パスワード再発行</h2>
        <div class="area-msg">
        </div>
        <label class="<?php if(!empty($err_msg['email'])) echo 'err' ;?>">
          ご指定のEmailアドレスにパスワード再発行用のURLと認証キーをお送りします。
        <input class="" type="text" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']);?>">
        </label>
        <div class="area-msg tx-red mb15">
          <?php
              if(!empty($err_msg['email'])){
                  echo $err_msg['email'];
              }
          ?>
        </div>
        <div class="btn-container mt20">
          <input type="submit" class="login-btn btn btn-mid" value="送信する" name="submit">
        </div>
      </form>
    </div>
  </section>
</div>
<?php
require 'footer.php';
?>

