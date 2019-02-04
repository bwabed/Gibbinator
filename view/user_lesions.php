<div class="mdl-grid mdl-layout__content">
    <?php
    if ($_SESSION['userType']['id'] == 2) { ?>
        <div class="mdl-card mdl-grid--no-spacing mdl-cell--12-col mdl-shadow--2dp">
            <div class="mdl-card__title mdl-color--indigo-500">
                <h6 class="mdl-card__title-text mdl-color-text--white">Lektionenliste</h6>
            </div>
            <table class="customTable mdl-data-table mdl-cell--12-col mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
                   id="lesion_table">
                <thead>
                <tr>
                    <th class="lesion_table">Bearbeiten</th>
                    <th class="lesion_table">Fach</th>
                    <th class="lesion_table">Klasse</th>
                    <th style="text-align: left" class="lesion_table">Programm und Themen</th>
                    <th style="text-align: left" class="lesion_table">Termine und Aufgaben</th>
                    <th class="lesion_table">Datum</th>
                    <th class="lesion_table">Von - Bis</th>
                    <th class="lesion_table">Zimmer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lektionen as $lektion) {
                    echo '
          <tr data-id="' . $lektion->id . '">
          <td>
          <form id="form-select-lesion-' . $lektion->id . '" action="/user/edit_lesion" method="get">
          <a href="#" id="form-select-lesion-button-' . $lektion->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="lesion_id" value="' . $lektion->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-lesion-button-' . $lektion->id . '").click(function(e) {
              $("#form-select-lesion-' . $lektion->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>';
                    foreach ($faecher as $fach) {
                        if ($fach->id == $lektion->fach_id) {
                            foreach ($klassen as $klasse) {
                                if ($fach->klassen_id == $klasse->id) {
                                    $klasenName = $klasse->name;
                                }
                            }
                            echo $fach->titel;
                        }
                    }
                    echo '</td>
          <td>' . $klasenName . '</td>
          <td style="text-align: left">' . nl2br($lektion->programm_themen) . '</td>
          <td style="text-align: left">' . nl2br($lektion->termine_aufgaben) . '</td>
          <td>';
                    foreach ($dates as $date) {
                        if ($date->id == $lektion->date_id) {
                            $dateString = strtotime($date->start_date);
                            $timeStart = strtotime($date->start_time);
                            $timeEnd = strtotime($date->end_time);
                            echo date('d.m.Y', $dateString);
                        }
                    }
                    echo '</td>
          <td>';
                    echo date('G:i', $timeStart) . ' - ' . date('G:i', $timeEnd);
                    echo '</td><td>';
                    foreach ($zimmer as $room) {
                        if ($room->id == $lektion->zimmer) {
                            echo $room->bezeichnung;
                        }
                    }
                    echo '</td></tr>
          ';
                }
                ?>
                </tbody>
            </table>
            <div class="mdl-card__actions mdl-card--border">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button mdl-color--red"
                        id="delete_button">
                    Lektionen löschen
                </button>
                <script type="text/javascript">
                    // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                    // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                    // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                    $(document).ready(function () {
                        $('#delete_button').click(function (e) {
                            e.preventDefault();

                            var selectedLesions = [];

                            $('table#lesion_table tbody tr td:first-child input').each(function (index, value) {
                                if (value.checked) {
                                    selectedLesions.push($(value).parent().parent().parent().data('id'));
                                }
                            });

                            if (selectedLesions.length != 0) {
                                $.post("/user/delete_selected_lesions", {lesions: selectedLesions})
                                    .done(function (data) {
                                        'use strict';
                                        var snackbarContainer = document.querySelector('#snackbar');
                                        var data = {message: 'Lektionen erfolgreich gelöscht.'};
                                        snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                    });
                            } else {
                                var snackbarContainer = document.querySelector('#snackbar');
                                var data = {message: 'Bitte mindestens eine Lektion wählen!'};
                                snackbarContainer.MaterialSnackbar.showSnackbar(data);
                            }

                            window.location.reload();
                        });
                    });
                </script>
            </div>
        </div>
    <?php } else {
        echo '<div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card__title mdl-color--indigo-500">
                <h2 class="mdl-card__title-text mdl-color-text--white">Nächste Lektionen</h2>
                </div>
                </div>';
        $counter = 0;
        foreach ($dates as $date) {
            foreach ($lektionen as $lektion) {
                if ($lektion->date_id == $date->id) {
                    $lesion = $lektion;
                    foreach ($zimmer as $room) {
                        if ($room->id == $lektion->zimmer) {
                            $inZimmer = $room;
                        }
                    }
                    foreach ($faecher as $fachRow) {
                        if ($lektion->fach_id == $fachRow->id) {
                            $fach = $fachRow;
                            foreach ($profs as $prof) {
                                if ($prof->id == $fach->lehrer_id) {
                                    $profEmail = $prof->email;
                                }
                            }
                            foreach ($klassen as $klasse) {
                                if ($klasse->id == $fach->klassen_id) {
                                    $klasenName = $klasse->name;
                                }
                            }
                        }

                    }
                }
            }
            $dateString = strtotime($date->start_date);
            $startTime = strtotime($date->start_time);
            $endTime = strtotime($date->end_time);
            if (date('Y-m-d') > $date->start_date) {
                continue;
            }
            if ($counter == 6) {
                break;
            }
            ?>

            <div class="mdl-card mdl-cell mdl-cell--4-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--indigo-500">
                    <h6 class="mdl-card__title-text mdl-color-text--white">
                        Lektion <?= $fach->titel . ', ' . date('d.m.Y', $dateString) . ', ' . date('H:i', $startTime) . ' - ' . date('H:i', $endTime) ?>
                        <?php
                        if ($_SESSION['userType']['id'] == 3) {
                            echo '<br/>Lehrperson: ' . $profEmail . '</h6></br>';
                        }
                        ?>
                        <h5 class="mdl-card__subtitle-text"><?= $klasenName ?></h5>
                </div>
                <div class="mdl-card__supporting-text">
                    <?php
                    if ($lesion->programm_themen != null) {
                        echo '<h5>Programm und Themen</h5>';
                        echo $lesion->programm_themen;
                    }
                    if ($lesion->termine_aufgaben != null) {
                        echo '<h5>Termine und Aufgaben</h5>';
                        echo $lesion->termine_aufgaben;
                    }
                    ?>
                    <h5>Zimmer</h5>
                    <?php
                    echo $inZimmer->bezeichnung;

                    ?>
                </div>
                <div class="mdl-card__menu">
                    <form action="/user/lesion_detail" method="get">
                        <input type="hidden" id="lesion_id" name="lesion_id" value="<?= $lesion->id ?>">
                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                            <i class="material-icons mdl-color-text--white">arrow_forward</i>
                        </button>
                    </form>
                </div>
            </div>
            <?php
            $counter++;
        }
        ?>
        <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
            <div class="mdl-card__title mdl-color--indigo-500">
                <h2 class="mdl-card__title-text mdl-color-text--white">Alle Lektionen</h2>
            </div>
            <table class="customTable mdl-data-table mdl-cell--12-col mdl-js-data-table mdl-shadow--2dp"
                   id="lesion_table">
                <thead>
                <tr>
                    <th class="lesion_table">Details</th>
                    <th class="lesion_table">Fach</th>
                    <th class="lesion_table">Klasse</th>
                    <th class="lesion_table">Lehrperson</th>
                    <th style="text-align: left" class="lesion_table">Programm und Themen</th>
                    <th style="text-align: left" class="lesion_table">Termine und Aufgaben</th>
                    <th class="lesion_table">Datum</th>
                    <th class="lesion_table">Von - Bis</th>
                    <th class="lesion_table">Zimmer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lektionen as $lektion) {
                    echo '
          <tr data-id="' . $lektion->id . '">
          <td>
          <form id="form-select-lesion-' . $lektion->id . '" action="/user/lesion_detail" method="get">
          <a href="#" id="form-select-lesion-button-' . $lektion->id . '" class="mdl-navigation__link">
          <i class="material-icons">list</i>
          </a>
          <input type="hidden" name="lesion_id" value="' . $lektion->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-lesion-button-' . $lektion->id . '").click(function(e) {
              $("#form-select-lesion-' . $lektion->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>';
                    foreach ($faecher as $fach) {
                        if ($fach->id == $lektion->fach_id) {
                            foreach ($profs as $prof) {
                                if ($fach->lehrer_id == $prof->id) {
                                    $profName = $prof->vorname . ' ' . $prof->nachname;
                                }
                            }
                            foreach ($klassen as $klasse) {
                                if ($klasse->id == $fach->klassen_id) {
                                    $klassenName = $klasse->name;
                                }
                            }
                            echo $fach->titel;
                        }
                    }
                    echo '</td>
          <td>' . $klassenName . '</td>
          <td>' . $profName . '</td>
          <td style="text-align: left">' . nl2br($lektion->programm_themen) . '</td>
          <td style="text-align: left">' . nl2br($lektion->termine_aufgaben) . '</td>
          <td>';
                    foreach ($dates as $date) {
                        if ($date->id == $lektion->date_id) {
                            $dateString = strtotime($date->start_date);
                            $timeStart = strtotime($date->start_time);
                            $timeEnd = strtotime($date->end_time);
                            echo date('d.m.Y', $dateString);
                        }
                    }
                    echo '</td>
          <td>';
                    echo date('G:i', $timeStart) . ' - ' . date('G:i', $timeEnd);
                    echo '</td><td>';
                    foreach ($zimmer as $room) {
                        if ($room->id == $lektion->zimmer) {
                            echo $room->bezeichnung;
                        }
                    }
                    echo '</td></tr>
          ';
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
