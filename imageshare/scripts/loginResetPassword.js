var RESETPW=(function(){
   var verifyEamil=function(){
        var eamilVal = $("#txtEmail").val();
        var lastAtPos = eamilVal.lastIndexOf('@');
        var lastDotPos = eamilVal.lastIndexOf('.');
        if(lastAtPos < lastDotPos && lastAtPos > 0 && eamilVal.indexOf('@@') == -1 && lastDotPos > 2 && (eamilVal.length - lastDotPos) > 2){
            window.document.getElementById("resetfrm").submit();
        }else{
            alert("Please Enter a valid email");
        }
    } 
    return{
        verifyEamil:verifyEamil
    }
})();
$(function(){
    $("#submitbtnReset").click(function(){
        RESETPW.verifyEamil();
    });
});