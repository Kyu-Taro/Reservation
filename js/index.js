$(function(){

    //フッターの高さ調節
    var $ftr = $('#footer');
    if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
        $ftr.attr({'style':'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'});
    };

    //変更完了をアラートする
    var $jsShowMsg=$('#js-show');
    var msg=$jsShowMsg.text();
    if(msg.replace(/^[\s ]+|[\s　]+$/g,"").length){
        $jsShowMsg.slideToggle('slow');
        setTimeout(function(){$jsShowMsg.slideToggle('slow');},5000);
    }
});