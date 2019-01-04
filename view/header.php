<!DOCTYPE html>
<html lang="de">
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

    <script type="text/javascript">
        $(document).ready(function () {

                $('#calendar').fullCalendar({
                    selectable: true,
                    locale: 'de',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    dayClick: function (date) {
                        $('#calendar').fullCalendar('changeView', 'agendaDay', date.format());
                    }
                })

            }
        );
    </script>

</head>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">Home</span>
            <div class="mdl-layout-spacer"></div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
                <li class="mdl-menu__item">About</li>
                <li class="mdl-menu__item">Contact</li>
                <li class="mdl-menu__item">Legal information</li>
            </ul>
        </div>
    </header>
    <div class="mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="">
            <img src="/view/images/user.jpg" alt="Das Bild konnte nicht gefunden werden" class="demo-avatar"/>
            <div class="demo-avatar-dropdown">
                <span>hello@example.com</span>
                <div class="mdl-layout-spacer"></div>

            </div>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
            <a class="mdl-navigation__link" href="/"><i class="mdl-color-text--blue-grey-400 material-icons"
                                                        role="presentation">home</i>Home</a>
            <a class="mdl-navigation__link" href="/user"><i class="mdl-color-text--blue-grey-400 material-icons"
                                                            role="presentation">inbox</i>Nachrichten</a>
            <a class="mdl-navigation__link" href="/user/create"><i class="mdl-color-text--blue-grey-400 material-icons"
                                                                   role="presentation">delete</i>Trash</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                       role="presentation">report</i>Spam</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons"
                                                       role="presentation">forum</i>Forums</a>
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
                                                       role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>
    </div>
    <body>
    <main class="mdl-layout__content mdl-color--grey-200">