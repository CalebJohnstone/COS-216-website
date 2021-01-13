<?php
    $footer = "<div class='footer'><p>Song information and images provided by Spotify and Deezer</p></div>";

    echo '<script type="text/javascript">';
    echo '$("#page-container").append("' . $footer . '");';
    echo '</script>';
?>