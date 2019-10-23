$(function(){

  /*==================================
  * フッターの位置を調整する
  ==================================*/
  let $ftr = $('.footer');
  if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
    $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
  }

  /*==================================
  * セッションメッセージの表示
  ==================================*/
  $message = $('.js-session-message')
  if($message.text().replace(/\s+/g, "").length){
    $message.slideToggle('slow');
    setTimeout(function(){
      $message.slideToggle('slow');
    },5000)
  }

  /*==================================
  * モーダル表示
  ==================================*/
  //変数宣言
  let $mask = $('.mask'),
      $images = $('.img-sub').find('img'),
      images = [],
      $mainImg = $('.slider-main-img'),
      currentImg = '';

  //スライダー用の画像配列を作成
  $images.each(function(i,v){
      images.push(this.src);
  });

  //メイン・サブ画像クリックでモーダルオープン
  $('.img-main img,.img-sub img').on('click',function(){
      $mainImg.attr('src',this.src);
      $mask.css('display','block');
      //現在の画像インデックスを格納しておく
      currentImg = $.inArray(this.src,images);
  });

  //次の画像へ
  $('.slider-right').on('click',function(){
      currentImg += 1;
      //一周した場合のインデックスを最初に戻す
      if(currentImg == images.length){
        currentImg = 0;
      }
      $mainImg.attr('src',images[currentImg]);
  });

  //前の画像へ
  $('.slider-left').on('click',function(){
      currentImg -= 1;
      //一周した場合のインデックスを最後に戻す
      if(currentImg == -1){
        currentImg = images.length-1;
      }
      $mainImg.attr('src',images[currentImg]);
  });

  //クローズ
  $('.close .fas').on('click',function(){
    $mask.css('display','none');
  });


  /*==================================
  * 商品登録時のテキストカウント
  ==================================*/
  let $textCount = $('.js-text-count');
  let $textArea = $('.mypage-product-commnet');
  $textArea.on('keyup',function(){
    let word = $(this).val().length;
    $textCount.text(word);
  });

  /*==================================
  * ライブプレビュー機能(画像のドロップ&ドラッグ)
  ==================================*/
  //変数
  $areDrop = $('.mypage-drop-prof');
  $inputFile = $('.mypage-form-img');
  //画像をドラッグ&オーバー時に枠線をつける
  $areDrop.on('dragover',function(){
  	$(this).css('border','3px #ccc dashed');
  })
  .on('dragleave',function(){
  	$(this).css('border','none');
  });

  //画像が変化した場合
  $inputFile.on('change',function(e){
  	  let  file = this.files[0],
           $img = $(this).siblings('.mypage-img'),
           $imgParent = $(this).parent(),
           fileReader = new FileReader();

      //読み込み完了時の処理
      fileReader.onload = function(e){
         $img.attr('src',e.target.result).show();
      }

      //読み込み
      fileReader.readAsDataURL(file);
  });

  
});