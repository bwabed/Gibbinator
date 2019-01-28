<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 19.01.2019
 * Time: 18:17
 */
?>
<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Nachrichten</h2>
        </div>
        <?php
        if ($_SESSION['userType'] ['id'] == 2) { ?>
            <table class="customTable mdl-cell--12-col mdl-data-table mdl-data-table--selectable mdl-js-data-table mdl-shadow--2dp message_table"
                   id="message_table">
                <thead>
                <tr>
                    <th class="message_table">Bearbeiten</th>
                    <th class="message_table">Titel</th>
                    <th style="text-align: left" class="message_table">Nachricht</th>
                    <th class="message_table">Erstellt am</th>
                    <th class="message_table">Klasse</th>
                    <th class="message_table">Fach (Lektion)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($nachrichten as $row) {
                    $erstelltAm = strtotime($row->erstellt_am);
                    echo '
          <tr data-id="' . $row->id . '">
          <td>
          <form id="form-select-message-' . $row->id . '" action="/user/edit_message" method="post">
          <a href="#" id="form-select-message-button-' . $row->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="nachrichten_id" value="' . $row->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-message-button-' . $row->id . '").click(function(e) {
              $("#form-select-message-' . $row->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>' . $row->titel . '</td>
          <td style="text-align: left">' . nl2br($row->text) . '</td>
          <td>' . date('d.m.Y', $erstelltAm) . '</td>
          <td>';
                    foreach ($klassen as $klasse) {
                        if ($klasse->id == $row->klassen_id) {
                            echo $klasse->name;
                        }
                    }
                    echo '</td>
          <td>';
                    if ($row->lektion_id != null) {
                        foreach ($lektionen as $lektion) {
                            if ($lektion->id == $row->lektion_id) {
                                foreach ($dates as $date) {
                                    if ($lektion->date_id == $date->id) {
                                        $dateString = strtotime($date->start_date);
                                        $startDate = date('d.m.Y', $dateString);
                                    }
                                }
                                foreach ($faecher as $fach) {
                                    if ($fach->id == $lektion->fach_id) {
                                        echo $fach->titel . ' ' . $startDate;
                                    }
                                }
                            }
                        }
                    } elseif ($row->fach_id != null) {
                        foreach ($faecher as $fach) {
                            if ($fach->id == $row->fach_id) {
                                echo $fach->titel;
                            }
                        }
                    }
                    echo '</td>
          </tr>
          ';
                }
                ?>
                </tbody>
            </table>
        <?php } elseif ($_SESSION['userType']['id'] == 3) { ?>
            <div id="messages">
                <?php
                foreach ($nachrichten as $nachricht) {
                    echo '<div style="border: grey; border-style: solid; border-radius: 5px; border-width: thin"><div class="mdl-card__title mdl-color--indigo-400">
<h6 class="mdl-card__title-text mdl-color-text--white">';
                    foreach ($teachers as $teacher) {
                        if ($teacher->id == $nachricht->erfasser_id) {
                            $creator = $teacher->email;
                        }
                    }
                    foreach ($lektionen as $lektion) {
                        if ($nachricht->lektion_id == $lektion->id) {
                            foreach ($faecher as $fach) {
                                if ($fach->id == $lektion->fach_id) {
                                    $fachName = $fach->titel;
                                }
                            }
                        }
                    }
                    foreach ($klassen as $klasse) {
                        if ($nachricht->klassen_id == $klasse->id) {
                            $klassenName = $klasse->name;
                        }
                    }
                    if (!empty($klassenName) && !empty($fachName)) {
                        echo 'Von: ' . $creator . ' -> ' . $klassenName . '/' . $fachName;
                    } elseif (!empty($klassenName) && empty($fachName)) {
                        echo 'Von: ' . $creator . ' -> ' . $klassenName;
                    } elseif (empty($klassenName) && empty($fachName)) {
                        echo 'Von: ' . $creator . ' -> Alle';
                    } elseif (empty($klassenName) && !empty($fachName)) {
                        echo 'Von: ' . $creator . ' -> ' . $fachName;
                    }
                    echo '</h6>
</div><div class="mdl-card__supporting-text"> ' . nl2br($nachricht->text) . '</div><div class="mdl-card__supporting-text" style="font-style: italic">
                    ' . date('d.m.Y', strtotime($nachricht->erstellt_am)) . '
                </div></div>';
                }
                ?>
            </div>
        <?php } ?>
        <div class="mdl-card__actions mdl-card--border mdl-grid">
            <?php
            if ($_SESSION['userType']['id'] == 2) {
                echo '
                <form action="/user/new_message" method="post">
                    <input type="hidden" id="user_id" name="user_id" value="' . $_SESSION['user']['id'] . '">
                    <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                            id="add_button">
                        Neue Nachricht
                    </button>
                </form>';?>
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
                        id="delete_button" style="margin-left: 5px">
                    Nachrichten löschen
                </button>
                <script type="text/javascript">
                    // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                    // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                    // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                    $(document).ready(function () {
                        $('#delete_button').click(function (e) {

                            var selectedMessage = [];

                            $('table#message_table tbody tr td:first-child input').each(function (index, value) {
                                if (value.checked) {
                                    selectedMessage.push($(value).parent().parent().parent().data('id'));
                                }
                            });

                            if (selectedMessage.length != 0) {
                                $.post("/user/delete_selected_message", {messages: selectedMessage})
                                    .done(function (data) {
                                        'use strict';
                                        var snackbarContainer = document.querySelector('#snackbar');
                                        var data = {message: 'Benutzer erfolgreich gelöscht.'};
                                        snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                    });
                            } else {
                                var snackbarContainer = document.querySelector('#snackbar');
                                var data = {message: 'Bitte mindestens ein Benutzer wählen!'};
                                snackbarContainer.MaterialSnackbar.showSnackbar(data);
                            }

                            window.location.reload();
                        });
                    });
                </script>
            <?php
            }
            ?>
        </div>
    </div>
</div>
