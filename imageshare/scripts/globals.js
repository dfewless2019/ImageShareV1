var GLOBALS = (function(){
    var init = function(){
        if (navigator.userAgent.search("MSIE") >= 0){
            var position = navigator.userAgent.search("MSIE") + 5;
            var end = navigator.userAgent.search("; Windows");
            var version = navigator.userAgent.substring(position,end);
            if(parseInt(version)<9){
                    window.location.href = 'http://localhost:8080/ImageSharev2/BadBrowser.php' 
            }
        }
    }
    return{
        init:init
    }
})();
$(function(){
   GLOBALS.init(); 
});
