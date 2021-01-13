function togglePasswordVisibility(){
    if($("input[name='password']").attr("type") === "password"){
        $("input[name='password']").attr("type", "text");
        $("button[id^='seePswd']").html("Hide");
    }
    else{
        $("input[name='password']").attr("type", "password");
        $("button[id^='seePswd']").html("See");
    }
}