<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gibbinator WebApp">

    <title>Gibbinator WebApp</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="/images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Gibbinator">
    <link rel="apple-touch-icon-precomposed" href="/images/ios-desktop.png">

    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="/images/gibb_logo.svg.png">

    <!-- Google Fonts -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- MDL CSS Framework -->
    <link href="/css/material.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-blue.min.css">


    <!-- FullCalendar Framework -->
    <script src="/js/moment.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/fullcalendar.js"></script>
    <script src="/js/de-ch.js"></script>
    <link href="/css/fullcalendar.css" rel="stylesheet"/>

    <!-- MDL JavaScrip Framework -->
    <script src="/js/material.js" defer></script>

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title mdl-color-text--white">Gibbinator</span>
            <div class="mdl-layout-spacer"></div>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                if ($_SESSION['userType']['id'] == 2) {
                    echo '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                <i class="material-icons" role="presentation">swap_vert</i>
            </button>
            <div class="mdl-tooltip" for="hdrbtn">
                Semesterplan hochladen<br>Vorlage herunterladen
            </div>
            <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right" for="hdrbtn">
                <li class=""><a class="mdl-menu__item" href="/download/get_file?file=' . urlencode("Sem_plan_vorlage.xlsx") . '">Excelvorlage herunterladen</a></li>
                <li class=""><a class="mdl-menu__item" href="/user/upload_plan">Semesterplan hochladen (csv)</a></li>
            </ul>';
                }
            }?>
        </div>
    </header>
    <div class="mdl-layout__drawer mdl-color--grey-700 mdl-color-text--white">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            $email = $_SESSION['user']['name'];
            $charPos = strpos($email, '@');
            echo '<header class="mdl-drawer-header mdl-color--indigo-500" id="loggedin">
                <img src="/images/gibb_logo.svg.png" alt="Profile Image couldn\'t load..."
                     class="gibbinator-avatar">
                <div class="gibbinator-avatar-dropdown">
                    <span>Willkommen ' . substr($email, 0, $charPos) . '</span>
                    <div class="mdl-layout-spacer"></div>
                    <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                        <i class="material-icons" role="presentation">arrow_drop_down</i>
                        <span class="visuallyhidden">Accounts</span>
                    </button>
                    <div class="mdl-tooltip mdl-color--white mdl-color-text--black" for="accbtn">
                        Logout /<br>Profil bearbeiten
                    </div>
                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                        <a class="mdl-navigation__link" href="/user/edit_profile"><li class="mdl-menu__item">Profil
                                bearbeiten</li></a>
                        <a class="mdl-navigation__link"
                                                      href="/user/logout"><li class="mdl-menu__item">Logout</li></a>
                    </ul>
                </div>
            </header>';
            switch ($_SESSION['userType']['id']) {
                case 1:
                    echo '
                    <nav class="demo-navigation mdl-navigation mdl-color--grey-800">
                        <a class="mdl-navigation__link" href="/admin/index"><i style="margin-right: 5px"
                                class="mdl-color-text--white material-icons"
                                role="presentation">group</i>Benutzer bearbeiten</a>
                        <a class="mdl-navigation__link" href="/admin/infra"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                               role="presentation">inbox</i>Infrastruktur</a>
                        <a class="mdl-navigation__link" href="/admin/classes"><i style="margin-right: 5px"
                                class="mdl-color-text--white material-icons"
                                role="presentation">delete</i>Klassen bearbeiten</a>
                    </nav>';
                    break;
                case 2:
                    echo '
                    <nav class="demo-navigation mdl-navigation mdl-color--grey-800">
                        <a class="mdl-navigation__link" href="/user/index"><i style="margin-right: 5px"
                                    class="mdl-color-text--white material-icons"
                                    role="presentation">home</i>Home</a>
                        <a class="mdl-navigation__link" href="/user/messages"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                                   role="presentation">inbox</i>Nachrichten</a>
                        <a class="mdl-navigation__link" href="/user/lesions"><i style="margin-right: 5px"
                                    class="mdl-color-text--white material-icons"
                                    role="presentation">calendar_today</i>Lektionen Liste</a>
                        <a class="mdl-navigation__link" href="/user/klassen"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                                   role="presentation">school</i>Klassen</a>
                    </nav>';
                    break;
                case 3:
                    echo '
                    <nav class="demo-navigation mdl-navigation mdl-color--grey-800">
                        <a class="mdl-navigation__link" href="/user/index"><i style="margin-right: 5px"
                                    class="mdl-color-text--white material-icons"
                                    role="presentation">home</i>Home</a>
                        <a class="mdl-navigation__link" href="/user/messages"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                                   role="presentation">inbox</i>Nachrichten</a>
                        <a class="mdl-navigation__link" href="/user/lesions"><i style="margin-right: 5px"
                                    class="mdl-color-text--white material-icons"
                                    role="presentation">calendar_today</i>Lektionen Liste</a>
                        <a class="mdl-navigation__link" href="/user/lehrer"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                                   role="presentation">person</i>Lehrerliste</a>
                        <a class="mdl-navigation__link" href="/user/stud_klassen"><i style="margin-right: 5px" class="mdl-color-text--white material-icons"
                                                                   role="presentation">people</i>Klassenliste</a>
                    </nav>';
                    break;
                default:
                    echo '
                    <nav class="mdl-navigation mdl-color--grey-800">
                        <a class="mdl-navigation__link" href="/user/index"><i style="margin-right: 5px"
                                    class="mdl-color-text--white material-icons"
                                    role="presentation">home</i>Home</a>
        
                    </nav>';
            } ?>
        <?php } else { ?>
            <header class="mdl-drawer-header mdl-color--indigo-500" id="loggedout">
                <img style="width: 200px" src="/images/gibb_logo.svg.png" alt="Bild konnte nicht geladen werden.."/>
            </header>
            <nav class="mdl-navigation mdl-color--grey-800">
                <a class="mdl-navigation__link" href="/"><i style="margin-right: 5px"
                                                            class="mdl-color-text--white material-icons"
                                                            role="presentation">perm_identity</i>Login</a>

            </nav>
        <?php } ?>
        <div class="mdl-layout-spacer"></div>
    </div>
    <main class="mdl-layout__content mdl-color--grey-200">