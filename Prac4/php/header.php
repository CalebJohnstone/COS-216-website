<?php
    require_once("config.php");
   
    echo "<script src='../javascript/header.js'></script>";

    $topnav = "<div id='topnav'>";
    $topnav .= "<a href='launchPage.php'><img id='logo' alt='RED PILL MUSIC' src='../images/logo.png'></a>";
    $topnav .= "<a href='trending.php'>Trending</a>";
    $topnav .= "<a href='newReleases.php'>New Releases</a>";
    $topnav .= "<a href='topRated.php'>Top Rated</a>";
    $topnav .= "<a href='featured.php'>Featured</a>";
    $topnav .= "<a href='calendar.php'>Calendar</a>";
    $topnav .= "<a href='tour.php'>Tour</a>";
    $topnav .= "<a href='login.php'>Login</a>";
    $topnav .= "<a href='signup.php'>Sign Up</a>";
    $topnav .= "</div>";

    echo $topnav;
?>