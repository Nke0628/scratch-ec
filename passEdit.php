<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「パスワード変更画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

$siteTitle = 'パスワード変更画面';
require 'head.php';
require 'header.php';

//================================
// 画面表示処理
//================================

//DBから情報を取得する
$dbFormData = getUser($_SESSION['user_id']);
debug('ユーザ情報:' . print_r($dbFormData,true));


if(!empty($_POST)){
  debug('POST送信されました');
  debug('POST情報' . print_r($_POST,true));

  //変数格納
  $pass = h($_POST['pass']);
  $pass_new = h($_POST['pass_new']);
  $pass_new_re = h($_POST['pass_new_re']);

  //入力必須バリデーション
  validRequired($pass,'pass');
  validRequired($pass_new,'pass_new');
  validRequired($pass_new_re,'pass_new_re');

  if(empty($err_msg)){
    //各形式のバリデーションを行

    //パスワード
    validPass($pass,'pass');

    //新しいパスワード
    validPass($pass_new,'pass_new');


    if(empty($err_msg)){

      //現在のパスワードが一致するか
      if(!password_verify($pass,$dbFormData['password'])){
        $err_msg['pass'] = MSG09;
      }
      
      //新パスワードが再入力パスワードと一致するか
      if($pass_new !== $pass_new_re){
        $err_msg['pass_new'] = MSG09;
      }

      if(empty($err_msg)){

        debug('バリデーションOK');

        try{

          $db = dbConnect();
          $sql = 'UPDATE users SET password = :password';
          $data = array(
            ':password' => password_hash($pass_new,PASSWORD_DEFAULT),
          ); 
          $stmt = queryPost($db,$sql,$data);
          if($stmt){

              //メール送信
              $from = mb_encode_mimeheader("PHOTOMARKET") .'<info@phoromarket.com>';
              $to = $dbFormData['email'];
              $subject = 'パスワード変更 | PHOTOMARKET';
              $message = <<<EOF
本メールアドレスのパスワードを変更しました。

この内容に心当たりがない場合は、
下記連絡先に連絡していただきますようお願い致します。

////////////////////////////////////////////////////
フォトマーケット
TEL:080-6128-6075
info@photomarket.com
////////////////////////////////////////////////////
EOF;

            sendMail($from, $to, $subject, $message);
            debug('パスワードを変更しました');
            debug('マイページへ遷移します');
            //遷移
            header('Location: mypage.php');
            exit;
          }else{
            debug('パスワード変更に失敗しました');
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

?>
<!-- メインコンテンツ -->
<div class="mypage-wrapper">
  <div class="main site-width">
    <h2 class="mypage-title text-center">パスワード変更</h2>
    <div class="mypage">
      <div class="mypage-left">
        <div class="mypage-form-wrapper bd-gold">
          <form method="POST" action="" enctype="multipart/form-data"> 
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err' ;?>">
              現在のパスワード
              <input class="" type="password" name="pass" value="<?php echo dbFormData('pass');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pass'])){echo $err_msg['pass'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['pass_new'])) echo 'err' ;?>">
              新しいパスワード
              <input class="" type="password" name="pass_new" value="<?php echo dbFormData('pass_new');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pass_new'])){echo $err_msg['pass_new'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['pass_new_re'])) echo 'err' ;?>">
              新しいパスワード(再入力)
              <input class="" type="password" name="pass_new_re" value="<?php echo dbFormData('pass_new_re');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pass_new_re'])){echo $err_msg['pass_new_re'];}?>
            </div>
            <div class="mypage-form-btn">
              <input type="submit" class="btn" value="更新する" name="submit">
            </div>
          </form>
        </div>
      </div>
      <?php require 'sidebar.php' ;?>
    </div>
  </div>
</div>
<?php
require 'footer.php';
?>

