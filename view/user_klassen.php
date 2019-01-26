<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-cell mdl-cell--12-col mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Klassen übersicht</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-cell mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="klassen_table">
            <thead>
            <tr>
                <th class="klassen_table">Bearbeiten</th>
                <th class="klassen_table">Name</th>
                <th class="klassen_table">Fächer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($klassen as $klasse) {
                echo '
          <tr data-id="' . $klasse->id . '">
          <td>
          <form id="form-select-klasse-' . $klasse->id . '" action="/user/edit_klasse" method="post">
          <a href="#" id="form-select-klasse-button-' . $klasse->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="klassen_id" value="' . $klasse->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-klasse-button-' . $klasse->id . '").click(function(e) {
              $("#form-select-klasse-' . $klasse->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>' . $klasse->name . '</td>
          <td>';
                $number = 0;
                $faecherString = 'Keine';
                foreach ($faecher as $fach) {
                    if ($fach->klassen_id == $klasse->id) {
                        if ($number == 0) {
                            $faecherString = $fach->titel;
                        } else {
                            $faecherString .= ', ' . $fach->titel;
                        }
                        $number++;
                    }
                }
                echo $faecherString;
                echo '</td>
          </tr>
          ';
            }
            ?>
            </tbody>
        </table>
        <div class="mdl-card__actions mdl-card--border">
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
                    id="delete_button">
                Klassen Löschen
            </button>
            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_button').click(function (e) {

                        var selectedUsers = [];

                        $('table#klassen_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedUsers.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedUsers.length != 0) {
                            $.post("/admin/delete_selected_klassen", {klassen: selectedUsers})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Klassen erfolgreich gelöscht.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens eine Klasse wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }

                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>