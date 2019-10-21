<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「プロフィール編集画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

$siteTitle = 'プロフィール編集画面';
require 'head.php';
require 'header.php';

//================================
// 画面表示処理
//================================

//DBから情報を取得する
$dbFormData = getUser($_SESSION['user_id']);

if(!empty($_POST)){
  debug('POST送信されました');
  debug('POST情報' . print_r($_POST,true));

  //変数格納
  $username = h($_POST['username']);
  $age = h($_POST['age']);
  $tel = h($_POST['tel']);
  $zip = h($_POST['zip']);
  $addr = h($_POST['addr']);
  $email = h($_POST['email']);
  $pic = $_FILES['pic'];

  //入力必須バリデーション
  validRequired($email,'email');

  if(empty($err_msg)){
    //各形式のバリデーションを行う

    //名前
    validMaxLen($username, 'username');

    //年齢
    validNumber($age, 'age');
    validMaxLen($age,'age',2);

    //電話番号
    validTel($tel, 'tel');

    //郵便番号
    validNumber($zip, 'zip');
    validMaxLen($age,'age',7);

    //住所
    validMaxLen($addr, 'addr');

    //emailの形式チェック
    if($dbFormData['email'] !== $email){
    validEmailDup($email,'email');
    validEmail($email,'email');
    validMaxLen($email,'email');      
    }

    if(empty($err_msg)){

      //写真のアップロード(バリデーション含む)
      $path = (!empty($pic['name'])) ?  uploadImg($pic,'pic') : '';

      if(empty($err_msg)){

        debug('バリデーションOK');

        try{

          $db = dbConnect();
          $sql = 'UPDATE users SET username = :username, age = :age, tel = :tel, zip = :zip, addr = :addr, email = :email, pic = :pic';
          $data = array(
            ':username' => $username,
            ':age' => $age,
            ':tel' => $tel,
            ':zip' => $zip,
            ':addr' => $addr,
            ':email' => $email,
            ':pic' => $path,
          ); 
          $stmt = queryPost($db,$sql,$data);
          if($stmt){
            debug('編集しました');
            debug('マイページへ遷移します');
            //遷移
            header('Location: mypage.php');
            exit;
          }else{
            debug('編集失敗しました');
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
    <h2 class="mypage-title text-center">プロフィール編集</h2>
    <div class="mypage">
      <div class="mypage-left">
        <div class="mypage-form-wrapper bd-gold">
          <form method="POST" action="" enctype="multipart/form-data"> 
            <label class="<?php if(!empty($err_msg['username'])) echo 'err' ;?>">
              名前
              <input class="" type="text" name="username" value="<?php echo dbFormData('username');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['username'])){echo $err_msg['username'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['age'])) echo 'err' ;?>">
              年齢
              <input class="" type="number" name="age" value="<?php echo dbFormData('age');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['age'])){echo $err_msg['age'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['tel'])) echo 'err' ;?>">
              電話番号(ハイフンなし)
              <input class="" type="text" name="tel" value="<?php echo dbFormData('tel');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['tel'])){echo $err_msg['tel'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['zip'])) echo 'err' ;?>">
              郵便番号
              <input class="" type="text" name="zip" value="<?php echo dbFormData('zip');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['zip'])){echo $err_msg['zip'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['addr'])) echo 'err' ;?>">
              住所
              <input class="" type="text" name="addr" value="<?php echo dbFormData('addr');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['addr'])){echo $err_msg['addr'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['email'])) echo 'err' ;?>">
              Email
              <input class="" type="text" name="email" value="<?php echo dbFormData('email');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['email'])){echo $err_msg['email'];}?>
            </div>
            <label>画像</label>
            <label class="mypage-drop-prof <?php if(!empty($err_msg['pic'])) echo 'err' ;?>">
              <input class="mypage-form-img" type="file" name="pic" value="<?php echo dbFormData('pic');?>">
              <img src="<?php echo dbFormData('pic');?>" class="mypage-img">
              ドラッグ&ドロップ
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pic'])){echo $err_msg['pic'];}?>
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

