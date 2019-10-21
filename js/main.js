$(function(){

  /*==================================
  * フッターの位置を調整する
  ==================================*/
  let $ftr = $('#footer');
  if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
  	$ftr.attr({'style':'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;'});
  }

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
  	//画像セットした枠のimg要素を取得する
  	if(this.files[0]['name'] !== ''){
  	  let  file = this.files[0],
           $img = $(this).siblings('.mypage-img'),
           $nowImg = $(this),
           $imgParent = $(this).parent(),
           fileReader = new FileReader();

      //読み込み完了時の処理
      fileReader.onload = function(e){
         $img.attr('src',e.target.result).show();
         $nowImg.css('height','auto');
         $imgParent.css('height','auto');
      }

      //読み込み
      fileReader.readAsDataURL(file); 		
  	}else{
      $img = $(this).siblings('.mypage-img'),
      $img.attr('src','').hide(); 
  	}

  });

  
});