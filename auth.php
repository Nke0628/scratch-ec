<?php

//ログイン情報の確認
if(!empty($_SESSION['login_time'])){
	//ログイン済みユーザ
	debug('セッション情報' . print_r($_SESSION,true));
	//有効期限の確認
	if($_SESSION['login_time'] + $_SESSION['login_limit'] < time()){
		//有効期限切れ
		debug('ログイン期限切れ。ログインページへ遷移します。');
		session_destroy();
		header('Location: login.php');
		exit;
	}else{
		//セッションの更新
		$_SESSION['login_time'] = time();
		//ログイン済みでログインページへアクセスした場合は、マイページへ遷移する
		$script_name_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
		$now_script = end($script_name_tmp);
		if($now_script === 'login.php'){
			debug('ログイン済み。マイページへ遷移します');
			header('Location: mypage.php');
			exit;			
		}
	}
}else{
	//未ログインユーザはログインページへ遷移する
	$script_name_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
	$now_script = end($script_name_tmp);
	if($now_script !== 'login.php'){
		debug('未ログインユーザ。ログインページへ遷移します');
		header('Location: login.php');
		exit;			
	}
}