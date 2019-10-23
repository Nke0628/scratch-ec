<?php

require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「商品登録画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require 'auth.php';

$siteTitle = '商品登録画面';
require 'head.php';
require 'header.php';

//================================
// 画面表示処理
//================================

//編集の場合はプロダクトIDがGETで渡される
$p_id = (!empty($_GET['p_id'])) ? h($_GET['p_id']) : '';
//プロダクトIDが空でなければ、プロダクト情報の取得
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'],$p_id) : '';
//プロダクト情報が空の場合は新規作成
$editFlag = (!empty($dbFormData)) ? true : false;
//商品idはあるが、商品情報がとれない場合、不正とみなしてマイページへ遷移
if(!empty($p_id) && empty($dbFormData)){
    header('Location: mypage.php');
    exit;
}
//カテゴリー情報取得
$dbCategoryData = getCategory();

//POST情報処理
if(!empty($_POST)){
    debug('POST送信されました');
    debug('POST情報' . print_r($_POST,true));

    //変数格納
    $name = h($_POST['name']);
    $category = h($_POST['category']);
    $comment = h($_POST['comment']);
    $price = h($_POST['price']);
    $pic1 = $_FILES['pic1'];
    $pic2 = $_FILES['pic2'];
    $pic3 = $_FILES['pic3'];

    //新規の場合
    if(empty($dbFormData)){

        //名前
        validMaxLen($name,'name');
        validRequired($name,'name');

        //カテゴリー
        validNumber($category,'category');
        validCategory($category,'category');

        //コメント
        validMaxLen($comment,'comment',500);
        
        //金額
        validRequired($price,'price');
        validNumber($comment,'comment');

    }else{

        //名前
        if($name !== $dbFormData['name']){
            validMaxLen($name,'name');
        }

        //カテゴリー
        if($category !== $dbFormData['category_id']){
            validNumber($category,'category');
            validCategory($category,'category');
        }

        //名前
        if($comment !== $dbFormData['comment']){
            validMaxLen($comment,'comment',500);
        }

        //金額
        if($price !== $dbFormData['price']){
            validNumber($comment,'comment');
        }
    }
    
    
    if(empty($err_msg)){
        
        //POSTあり、DBあり→POSTのパス ※DB変更なしの場合はPOSTされない前提
        //POSTあり、DBなし→POSTパス
        //POSTなし、DBあり→DBパス
        //POSTなし、DBなし→DBパス
        $path1 = (!empty($pic1['name'])) ?  uploadImg($pic1,'pic1') : '';
        $path1 = (empty($path1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $path1; 
        $path2 = (!empty($pic2['name'])) ?  uploadImg($pic2,'pic2') : '';
        $path2 = (empty($path2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $path2; 
        $path3 = (!empty($pic3['name'])) ?  uploadImg($pic3,'pic3') : '';
        $path3 = (empty($path3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $path3; 

        if(empty($err_msg)){
            debug('バリデーションOK');
            try{
                $db = dbConnect();
                if($editFlag){
                    $sql = 'UPDATE products SET name = :name, category_id = :category, comment = :comment, price = :price, poc1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE id = :p_id AND delete_flag = 0';
                    $data = array(
                        ':name' => $name,
                        ':category' => $category,
                        ':comment' => $comment,
                        ':price' => $price,
                        ':pic1' => $pic1,
                        ':pic2' => $pic2,
                        ':pic3' => $pic3,
                    ); 
                }else{
                    $sql = 'INSERT INTO products(name,category_id,comment,price,pic1,pic2,pic3,user_id,create_date) VALUES(:name, :category, :comment, :price, :pic1, :pic2, :pic3,:user_id, :create_date)';
                    $data = array(
                        ':name' => $name,
                        ':category' => $category,
                        ':comment' => $comment,
                        ':price' => $price,
                        ':pic1' => $path1,
                        ':pic2' => $path2,
                        ':pic3' => $path3,
                        ':user_id' => $_SESSION['user_id'],
                        ':create_date' => date('Y:m:d H:i:s'),
                    ); 
                }
                $stmt = queryPost($db,$sql,$data);
                if($stmt){
                    debug('商品を登録しました');
                    debug('マイページへ遷移します');
                    $_SESSION['msg_success'] = SUC03;
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

?>
<!-- メインコンテンツ -->
<div class="mypage-wrapper">
  <div class="main site-width">
    <h2 class="mypage-title text-center">商品登録</h2>
    <div class="mypage">
      <div class="mypage-left">
        <div class="mypage-form-wrapper bd-gold">
          <form method="POST" action="" enctype="multipart/form-data"> 
            <label class="<?php if(!empty($err_msg['name'])) echo 'err' ;?>">
              商品名<span class="box-require">必須</span>
              <input class="" type="text" name="name" value="<?php echo dbFormData('name');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['name'])){echo $err_msg['name'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['category'])) echo 'err' ;?>">
              カテゴリー<span class="box-require">必須</span>
            <select class="" name="category">
                <option value="0" selected>選択してください</option>
                <?php foreach ($dbCategoryData as $key => $value): ?>
                    <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                <?php endforeach ?>
            </select>
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['category'])){echo $err_msg['category'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['comment'])) echo 'err' ;?>">
              商品詳細
              <textarea class="mypage-product-commnet" name="comment" rows="15"><?php echo dbFormData('comment');?></textarea>
              <p class="text-count"><span class="js-text-count">0</span>/500文字</p>
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['comment'])){echo $err_msg['comment'];}?>
            </div>
            <label class="<?php if(!empty($err_msg['price'])) echo 'err' ;?>">
              金額<span class="box-require">必須</span>
              <input class="" type="number" name="price" value="<?php echo dbFormData('price');?>">
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['price'])){echo $err_msg['price'];}?>
            </div>
            <label>画像</label>
            <label class="mypage-drop-prof <?php if(!empty($err_msg['pic1'])) echo 'err' ;?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input class="mypage-form-img" type="file" name="pic1" value="<?php echo dbFormData('pic1');?>">
              <img src="<?php echo dbFormData('pic1');?>" class="mypage-img">
              ドラッグ&ドロップ
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pic1'])){echo $err_msg['pic1'];}?>
            </div>
            <label>画像</label>
            <label class="mypage-drop-prof <?php if(!empty($err_msg['pic2'])) echo 'err' ;?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input class="mypage-form-img" type="file" name="pic2" value="<?php echo dbFormData('pic2');?>">
              <img src="<?php echo dbFormData('pic2');?>" class="mypage-img">
              ドラッグ&ドロップ
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pic2'])){echo $err_msg['pic2'];}?>
            </div>
            <label>画像</label>
            <label class="mypage-drop-prof <?php if(!empty($err_msg['pic3'])) echo 'err' ;?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input class="mypage-form-img" type="file" name="pic3" value="<?php echo dbFormData('pic3');?>">
              <img src="<?php echo dbFormData('pic3');?>" class="mypage-img">
              ドラッグ&ドロップ
            </label>
            <div class="area-msg tx-red">
              <?php if(!empty($err_msg['pic3'])){echo $err_msg['pic3'];}?>
            </div>
            <div class="mypage-form-btn">
              <input type="submit" class="btn" value="登録する" name="submit">
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

