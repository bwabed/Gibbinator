<script type="text/javascript">
    $(document).ready(function () {
            $('#calendar').fullCalendar({
                events: [
                    <?php
                    $row = 0;
                    foreach ($lektionen as $lektion) {
                        foreach ($faecher as $fach) {
                            if ($lektion->fach_id == $fach->id) {
                                $fachName = $fach->titel;
                            }
                        }
                        foreach ($daten as $datum) {
                            if ($datum->id == $lektion->date_id) {
                                $allDay;
                                if ($datum->all_day == 0) {
                                    $allDay = 'false';
                                } elseif ($datum->all_day == 1) {
                                    $allDay = 'true';
                                }
                                if ($row == 0) {
                                    echo '{ id: "' . $lektion->id . '", title: "' . $fachName . '", start: "' . $datum->start_date . 'T' . $datum->start_time . '", end: "' . $datum->end_date . 'T' . $datum->end_time . '", allDay: ' . $allDay . '}';
                                } elseif ($row > 0) {
                                    echo ',{ id: "' . $lektion->id . '", title: "' . $fachName . '", start: "' . $datum->start_date . 'T' . $datum->start_time . '", end: "' . $datum->end_date . 'T' . $datum->end_time . '", allDay: ' . $allDay . '}';
                                }
                                $row++;
                            }
                        }
                    }
                    ?>
                ],
                eventClick: function (eventObj) {
                    window.open('/user/lesion_detail?lesion_id=' + eventObj.id, '_self');
                },
                selectable: true,
                locale: 'de',
                weekNumbers: true,
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
<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--9-col mdl-shadow--2dp mdl-color--white">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h1 class="mdl-card__title-text mdl-color-text--white">Kalender</h1>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="mdl-card mdl-cell mdl-cell--3-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h6 class="mdl-card__title-text mdl-color-text--white">Letzte Nachrichten</h6>
        </div>
        <div id="messages">
            <?php
            foreach ($nachrichten as $nachricht) {
                echo '<div style="border: grey; border-style: solid; border-radius: 5px; border-width: thin"><div class="mdl-card__title mdl-color--indigo-400">
                            <h6 class="mdl-card__title-text mdl-color-text--white" style="font-size: 10pt">';
                foreach ($profs as $prof) {
                    if ($prof->id == $nachricht->erfasser_id) {
                        $erfasser = $prof->email;
                    }
                }
                $lektionCount = 0;
                foreach ($lektionen as $lektion) {
                    if ($lektion->id == $nachricht->lektion_id) {
                        foreach ($faecher as $fach) {
                            if ($fach->id == $lektion->fach_id) {
                                $fachName = $fach->titel;
                            }
                        }
                        $lektionCount = 1;
                    }
                }
                foreach ($klassen as $klasse) {
                    if ($klasse->id == $nachricht->klassen_id) {
                        $klassenName = $klasse->name;
                    }
                }
                if ($_SESSION['userType']['id'] == 2) {
                    if (!empty($klassenName) && !empty($fachName)) {
                        echo 'An ' . $klassenName . '/' . $fachName;
                    } elseif (!empty($klassenName) && empty($fachName)) {
                        echo 'An ' . $klassenName;
                    } elseif (empty($klassenName) && empty($fachName)) {
                        echo 'An ' . 'Alle';
                    } elseif (empty($klassenName) && !empty($fachName)) {
                        echo 'An ' . $fachName;
                    }
                } else {
                    if (!empty($klassenName) && !empty($fachName)) {
                        echo 'Von: ' . $erfasser . ' -> ' . $klassenName . '/' . $fachName;
                    } elseif (!empty($klassenName) && empty($fachName)) {
                        echo 'Von: ' . $erfasser . ' -> ' . $klassenName;
                    } elseif (empty($klassenName) && empty($fachName)) {
                        echo 'Von: ' . $erfasser . ' -> ' . 'Alle';
                    } elseif (empty($klassenName) && !empty($fachName) && $lektionCount == 1) {
                        echo 'Von: ' . $erfasser . ' -> ' . $fachName;
                    } else {
                        echo 'Von: ' . $erfasser;
                    }
                }
                echo '</h6>
</div><div class="mdl-card__supporting-text">';
                echo nl2br($nachricht->text);
                echo '</div></div>';

            }
            ?>
        </div>
        <?php if ($_SESSION['userType']['id'] == 2) { ?>
            <div class="mdl-card--border mdl-card__actions">
                <form action="/user/new_message" method="post">
                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
                    <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                            id="add_button" href="/admin/new_message">
                        Neue Nachricht
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>