<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-cell mdl-cell--6-col mdl-grid--no-spacing mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h1 class="mdl-card__title-text mdl-color-text--white">Neue Klasse</h1>
        </div>
        <form action="/user/create_klasse" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_klassenname" name="new_klassenname"
                           value="<?= isset($_POST['new_klassenname']) ? htmlspecialchars(strip_tags($_POST['new_klassenname'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_klassenname">Klassenname*</label>
                </div>
                <select class="mdl-cell--12-col klassen_lp_select"
                        name="klassen_lp_select"
                        id="klassen_lp_select">
                    <?php
                    echo '<option value="">Klassenlehrperson wählen..*</option>';
                    foreach ($lehrer as $row) {
                        if (!empty($_POST['klassen_lp_select']) && rawurldecode($_POST['klassen_lp_select']) == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        }
                    }
                    ?>
                </select>
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
            </div>
            <div class="mdl-card__actions mdl-card--border send-button">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="create">
                    Klasse erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-cell mdl-cell--6-col mdl-grid--no-spacing mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h1 class="mdl-card__title-text mdl-color-text--white">Neues Fach</h1>
        </div>
        <form action="/user/create_fach" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_fachtitle" name="new_fachtitle"
                           value="<?= isset($_POST['new_fachtitle']) ? htmlspecialchars(strip_tags($_POST['new_fachtitle'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_fachtitle">Titel*</label>
                </div>
                <select class="mdl-cell--12-col new_fach_lp_select"
                        name="new_fach_lp_select"
                        id="new_fach_lp_select">
                    <?php
                    echo '<option value="">Lehrperson wählen..*</option>';
                    foreach ($lehrer as $row) {
                        if (!empty($_POST['new_fach_lp_select']) && rawurldecode($_POST['new_fach_lp_select']) == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        }
                    }
                    ?>
                </select>
                <select class="mdl-cell--12-col klassen_select"
                        name="klassen_select"
                        id="klassen_select">
                    <?php
                    echo '<option value="">Klasse wählen..*</option>';
                    foreach ($allKlassen as $row) {
                        if (!empty($_POST['klassen_select']) && rawurldecode($_POST['klassen_select']) == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->name . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
            </div>
            <div class="mdl-card__actions mdl-card--border send-button">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="create">
                    Fach erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-cell--12-col mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Klassen übersicht</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
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
        <div class=" mdl-card__actions mdl-card--border">
            <button style="margin-bottom: 5px"
                    class=" mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
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
                            $.post("/user/delete_selected_klassen", {klassen: selectedUsers})
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
    <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-cell--12-col mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Klassen übersicht</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="faecher_table">
            <thead>
            <tr>
                <th class="faecher_table">Bearbeiten</th>
                <th class="faecher_table">Name</th>
                <th class="faecher_table">Fächer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($faecher as $fach) {
                echo '
          <tr data-id="' . $klasse->id . '">
          <td>
          <form id="form-select-fach-' . $klasse->id . '" action="/user/edit_fach" method="post">
          <a href="#" id="form-select-fach-button-' . $klasse->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="fach_id" value="' . $klasse->id . '">
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
        <div class=" mdl-card__actions mdl-card--border">
            <button style="margin-bottom: 5px"
                    class=" mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
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
                            $.post("/user/delete_selected_klassen", {klassen: selectedUsers})
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