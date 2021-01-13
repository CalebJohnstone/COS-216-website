O<?php
    require_once('sidebar.php');
    
    $footer = "<div class='footer'></div>";

    echo '<script type="text/javascript">';
    echo '$("body").append("' . $footer . '");';
    echo '</script>';

    echo '<script src="../javascript/footer.js"></script>';
    echo '<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
?>