<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-18
 * Time: 13:07
 */
?>

<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-card-form mdl-grid mdl-grid--no-spacing  mdl-cell--12-col mdl-shadow--2dp" id="klassen_results">
        <div class="mdl-card__title">
            <h3>Klassen</h3>
        </div>
        <table class="mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp" id="klassen_table">
            <thead>
            <tr>
                <th class="klassen_table">Bearbeiten</th>
                <th class="klassen_table">Name</th>
                <th class="klassen_table">Klassenlehrperson</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($klassen as $row) {
                echo '
          <tr data-id="' . $row->id . '">
          <td>
          <form id="form-select-klasse-' . $row->id . '" action="/admin/edit_klasse" method="post">
          <a href="#" id="form-select-klasse-button-' . $row->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="klassen_id" value="' . $row->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-klasse-button-' . $row->id . '").click(function(e) {
              $("#form-select-klasse-' . $row->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>' . $row->name . '</td>
          <td>';
                foreach ($lehrer as $lp) {
                    if ($lp->id == $row->klassen_lp) {
                        echo $lp->vorname . ' ' . $lp->nachname;
                    }
                }
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
            <a class="addUserButton mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button"
               id="add_button" href="/admin/new_klasse">
                Neue Klasse
            </a>

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
</div>