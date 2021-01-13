function pauseOthers(current) {
    $("audio").not(current).each(function (index, audio) {
        audio.pause();
    });
}