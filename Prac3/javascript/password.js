function togglePasswordVisibility(){
    if($("input[name='password']").attr("type") === "password"){
        $("input[name='password']").attr("type", "text");
        $("#seePswdSignUp").html("Hide");
    }
    else{
        $("input[name='password']").attr("type", "password");
        $("#seePswdSignUp").html("See");
    }
}