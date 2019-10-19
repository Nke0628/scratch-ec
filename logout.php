<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログアウト　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//ログイン認証
require('auth.php');

//ログアウト処理
session_destroy();
debug('ログアウトします');
debug('ログインページへ遷移します');
header('Location: login.php');
exit;