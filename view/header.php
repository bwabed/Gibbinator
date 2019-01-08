<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Webapplikation der Gibbinator App für Lehrpersonen">


    <title>Gibbinator WebApp</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">

    <link href="/view/css/material.min.css" rel="stylesheet">
    <script src="/view/js/material.min.js"></script>

    <!-- FullCalendar Framework -->
    <script src="/view/js/moment.min.js"></script>
    <script src="/view/js/jquery.min.js"></script>
    <script src="/view/js/jquery-ui.min.js"></script>
    <script src="/view/js/fullcalendar.js"></script>
    <script src="/view/js/de-ch.js"></script>
    <link href="/view/css/fullcalendar.css" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="/view/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">Gibbinator</span>
            <div class="mdl-layout-spacer"></div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
                <li class="mdl-menu__item">Über uns</li>
                <li class="mdl-menu__item">Kontakt</li>
            </ul>
        </div>
    </header>
        <div class="mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php if (isset($_SESSION ['loggedin']) && $_SESSION ['loggedin'] == true) { ?>
                <header class="mdl-drawer-header" id="loggedin">
                    <img src="../view/images/user.jpg" alt="Profile Image couldn't load..."
                         class="gibbinator-avatar">
                    <div class="gibbinator-avatar-dropdown">
                        <span>Willkommen <?php echo $_SESSION ['user']['name'] ?>!</span>
                        <div class="mdl-layout-spacer"></div>
                        <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                            <i class="material-icons" role="presentation">arrow_drop_down</i>
                            <span class="visuallyhidden">Accounts</span>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                            <li class="mdl-menu__item"><a class="mdl-navigation__link" href="/user/edit_profile">Angaben
                                    bearbeiten</a></li>
                            <li class="mdl-menu__item"><a class="mdl-navigation__link"
                                                          href="/user/logout">Logout</a></li>
                            <li class="mdl-menu__item"><a class="mdl-navigation__link" href="/user/register"><i
                                            class="material-icons">add</i>Add another account...</a></li>
                        </ul>
                    </div>
                </header>
            <?php } else { ?>
                <header class="demo-drawer-header" id="loggedout">
                    <img src="view/images/user.jpg" alt="Bild konnte nicht geladen werden.."/>
                </header>
            <?php } ?>
            <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
                <?php if (isset($_SESSION ['loggedin']) && $_SESSION ['loggedin'] == true) { ?>
                    <a class="mdl-navigation__link" href="/user/index"><i
                                class="mdl-color-text--blue-grey-400 material-icons"
                                role="presentation">home</i>Home</a>
                <?php } else { ?>
                    <a class="mdl-navigation__link" href="/"><i
                                class="mdl-color-text--blue-grey-400 material-icons"
                                role="presentation">home</i>Home</a>
                <?php } ?>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">inbox</i>Nachrichten</a>
                <a class="mdl-navigation__link" href="/user/lessions"><i
                            class="mdl-color-text--blue-grey-400 material-icons"
                            role="presentation">delete</i>Lektionen Liste</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">report</i>Klassen</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">forum</i>Lehrer Liste</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">flag</i>Updates</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">local_offer</i>Promos</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">shopping_cart</i>Purchases</a>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">people</i>Social</a>
                <div class="mdl-layout-spacer"></div>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                           role="presentation">help_outline</i><span
                            class="visuallyhidden">Help</span></a>
            </nav>
        </div>

    <main class="mdl-layout__content mdl-color--grey-200">