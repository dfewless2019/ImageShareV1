var REGISTRATION = (function(){
    function VerifyPW(){
        var p1 = $("#password1").val(),
        p2 = $("#password2").val(),
        measageSpan=$("#passWordConfirm");
        if(p1===p2 && p1!=="" && p1.length>5){
            measageSpan.css("color","black");
            measageSpan.html("<img title='Passwords Match' width='20' height='20' src='../images/match.png'/>");
        }else{
            measageSpan.css("color","red");
            measageSpan.html("<img title='Passwords Do Not Match' width='20' height='20' src='../images/nomatch.png'/>");
        }
    }
    function setSelectionRange(input, selectionStart, selectionEnd) {
      if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
      }
      else if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
      }
    }
    function setCaretToPos (input, pos) {
      setSelectionRange(input, pos, pos);
    }
    function ClearInitialized(input,InitalizedTo){
        var i = $("#"+input), e = i.val();
        if(e===InitalizedTo){
            if(input.indexOf("password")>-1){
                $("#"+input).get(0).setAttribute('type', 'password');
            }
            i.val("");
            i.caretToStart();
        }
    }
    function ResetOnBlur(input,InitalizedTo){
        $("#"+input).blur(function(){

            if($("#"+input).val()===""){
                if(input.indexOf("password")>-1){
                    $("#"+input).get(0).setAttribute('type', 'text');
                }
                $("#"+input).val(InitalizedTo);
            }
        });  
    }
    function verifyFrontEnd(){
        $("#ErrorDiv").html("");
        var allInputs = $(":input"),submit=false,errorFields=[];
        if(allInputs.length>0){
            $.each(allInputs,function(){
               if($(this).attr("type")!=="button" && $(this).attr("id")!=="recaptcha_challenge_field"){
                    var lenCap = $(this).attr("maxlength"); 
                    var value = $(this).val();
                    var initializedtoVar = $(this).attr("initalizedto");
                    if(value!==""&& value!==initializedtoVar && parseInt(lenCap)>=parseInt(value.length)){
                        submit=true;
                    }else{
                        
                        if($(this).attr("id")!=="recaptcha_response_field"){
                            submit=false;
                            errorFields.push(
                                {
                                    name:$(this).attr("id"),
                                    initilizedto:$(this).attr("initalizedto")
                                }
                        
                            );
                        }else{
                            if(value.length<=0){
                                errorFields.push(
                                    {
                                        name:$(this).attr("id"),
                                        initilizedto:$(this).attr("initalizedto")
                                    }
                                );
                                    submit=false;
                            }else{
                                
                            }
                        }
                    }
               }
            });
            if(submit && errorFields.length===0){
                 window.document.getElementById("regForm").submit();
            }else{
                for(var error in errorFields){
                    $("#"+errorFields[error].name).css("background-color","red");
                    $("#"+errorFields[error].name).val(errorFields[error].initilizedto);
                }
                $("#ErrorDiv").html("All fields are required please update the highlighted fields and click sign up again Thanks");
                $("#ErrorDiv").css("color","red");
            }
        }
    }
    return{
        setCaretToPos:setCaretToPos,
        VerifyPW:VerifyPW,
        ClearInitialized:ClearInitialized,
        ResetOnBlur:ResetOnBlur,
        verifyFrontEnd:verifyFrontEnd
    }
})(); 

$(function(){
    $("#password2").keyup(function(){REGISTRATION.VerifyPW()});
    $("#password1").keyup(function(){REGISTRATION.VerifyPW()});
    var allInputs = $(":input");
    if(allInputs.length>0){
        $.each(allInputs, function(){
            $(this).keydown(function(){
                REGISTRATION.ClearInitialized($(this).attr("ID"),$(this).attr("initalizedto"));
                REGISTRATION.ResetOnBlur($(this).attr("ID"),$(this).attr("initalizedto"));
            });
        });
    }
    $("#submitbtn").click(function(){
       REGISTRATION.verifyFrontEnd(); 
    });
    
});


