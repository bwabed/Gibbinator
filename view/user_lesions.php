<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-grid--no-spacing mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--grey-500">
            <h6 class="mdl-card__title-text">Lektionenliste</h6>
        </div>
        <?php
        if ($_SESSION['userType']['id'] == 2) { ?>
            <table class="mdl-data-table mdl-cell--12-col mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
                   id="lesion_table">
                <thead>
                <tr>
                    <th class="lesion_table">Bearbeiten</th>
                    <th class="lesion_table">Fach</th>
                    <th class="lesion_table">Programm und Themen</th>
                    <th class="lesion_table">Termine und Aufgaben</th>
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
                            echo $fach->titel;
                        }
                    }
                    echo '</td>
          <td>' . $lektion->programm_themen . '</td>
          <td>' . $lektion->termine_aufgaben . '</td>
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

                        var selectedLesions = [];

                        $('table#lesion_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedLesions.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedLesions.length != 0) {
                            $.post("/user/delete_selected_lesion", {lesions: selectedLesions})
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
        <?php } else { ?>

        <?php } ?>
    </div>
</div>