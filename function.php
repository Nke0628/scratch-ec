<?php

//設定情報読み込み
require 'config.php';

/**************************************************
* エラー/ログ設定
**************************************************/
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

ini_set('log_errors', 'on');
ini_set('error_log', '/var/log/scratch_php_web_error.log');

$debug_flag = true;

/**
* ログ関数
*
* @param string $str ログに残す値
*/
function debug($str){
	global $debug_flag;
	if(!empty($debug_flag)){
		error_log('デバッグ: ' .$str);
	}
}

/**************************************************
* セッション
**************************************************/
//セッション保存場所の変更
session_save_path('/var/tmp');
//有効期限を設定する
ini_set('session.gc_maxlifetime', 60*60*24*30);
//クッキーの有効期限を伸ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
//セッションスタート
session_start();
//セキュリティ対策
session_regenerate_id();

/**************************************************
* エラーメッセージ
**************************************************/
$err_msg = [];
define('MSG01','必須入力です');
define('MSG02','そのEmailはすでに登録されています');
define('MSG03','最大入力値を超えています');
define('MSG04','Emailの形式が正しくありません');
define('MSG05','入力文字数が少ないです');
define('MSG06','半角で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください');
define('MSG08','入力が一致しておりません');
define('MSG09','パスワードがアンマッチです');
define('MSG10','メール送信に失敗しました');
define('MSG11','認証キーが違います');
define('MSG12','有効期限が切れています');
define('SUC01','メール送信しました。');



/**************************************************
* DB関連
**************************************************/

/**
* DB接続を行います
*
* @return PDOobject
*/
function dbConnect(){
  //DBへの接続準備
  $dsn = 'mysql:dbname='. DB_NAME .';host=localhost;charset=utf8';
  $user = DB_USER;
  $password = DB_PASS;
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}

/**
* DB接続を行います
*
* @return PDOobject
*/
function queryPost($db, $sql, $data){
	$stmt = $db->prepare($sql);
	$stmt->execute($data);

	if($stmt){
		debug('クエリ成功');
	}else{
		debug('クエリ');
		return 0;
	}

	return $stmt;
}
/**************************************************
* バリデーション
**************************************************/
/**
* 必須入力
* @param string $str 入力値
* @param string $key エラーキー
*/
function validRequired($str, $key){
	if(empty($str)){
		global $err_msg;
		$err_msg[$key] = MSG01;
	}
}

/**
* emailの形式チェック
* @param string $str 入力値
* @param string $key エラーキー
*/
function validEmail($str, $key){
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG04;	
	}
}

/**
* emailの重複チェック
* @param string $email 入力されたEmail
*/
function validEmailDup($email){
	global $err_msg;
	try{
		$db = dbConnect();
		$sql = 'SELECT * FROM users WHERE email = :email AND delete_flag = 0';
		$data = array(':email' => $email);
		$stmt = queryPost($db, $sql, $data);
		if($stmt->rowCount() > 0){
			debug('Emailは登録されています');
			$err_msg['email'] = MSG02; 
		}
	}catch(Exception $e){
		error_log('エラー発生' . $e->getMessage());
		$err_msg['common'] = MSG07;
	}
}

/**
* 最大値入力チェック
* @param string $str 入力値
* @param string $key エラーキー
* @param int $max 最大入力値
*/
function validMaxLen($str, $key, $max = 255){
	if(empty($str)){
		if(mb_strlen($str) > $max){
			global $err_msg;
			$err_msg[$key] = MSG03;
		}
	}
}

/**
* 最小値入力チェック
* @param string $str 入力値
* @param string $key エラーキー
* @param int $min 最小入力値
*/
function validMinLen($str, $key, $min = 6){
	if(empty($str)){
		if(mb_strlen($str) < $min){
			global $err_msg;
			$err_msg[$key] = MSG05;
		}
	}
}

/**
* 半角チェック
* @param string $str 入力値
* @param string $key エラーキー
*/
function validHalf($str, $key){
	if(!preg_match('/^[a-zA-Z0-9]+$/', $str)){
		global $err_msg;
		$err_msg[$key] = MSG06;
	}
}

/**
* 入力値マッチチェック
* @param string $str 入力値
* @param string $ste2 入力値2
* @param string $key エラーキー
*/
function validMatch($str, $str2, $key){
	if($str !== $str2){
		global $err_msg;
		$err_msg[$key] = MSG08;	
	}
}
/**************************************************
* エスケープ
**************************************************/
/**
* エスケープ処理
*/
function h($str){
	return htmlspecialchars($str,ENT_QUOTES,'utf-8');
}

/**************************************************
* 送信データ保存
**************************************************/
function dbFormData($key){

}


/**************************************************
* 認証
**************************************************/
/**
* パスワード再発行時の認証コードを作成します
*
* @param int $length キーの長さ(デフォルト8桁)
* @return stirng
*/
function makeAuthKey($length = 8){
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$chars_array = str_split($chars);
	$str = '';
	for($i = 0; $i < 8; $i++){
		$str .= $chars[mt_rand(0,61)];
	}
	return $str;
}


/**************************************************
* メール
**************************************************/
/**
* メール送信を行います
*
* @param string $from 送信元
* @param string $to 送信先
* @param string $subject タイトル
* @param string $message メッセージ
*/
function sendMail($from, $to, $subject, $message){
    if(!empty($to) && !empty($subject) && !empty($message)){
    	mb_language('japanese');
    	mb_internal_encoding('UTF-8');
    	$result = mb_send_mail($to, $subject, $message, 'FROM:' . $from);
    	if($result){
    	    debug('メール送信成功しました');
    	}else{
    		debug('メール送信失敗しました');
    	}
    }
}