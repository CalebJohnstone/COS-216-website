function pauseOthers(ele) {
    $("audio").not(ele).each(function (index, audio) {
        audio.pause();
    });
}