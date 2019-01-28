<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Neues Gebäude</h2>
        </div>
        <form action="/admin/add_building" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="build_name" name="build_name">
                    <label class="mdl-textfield__label" for="build_name">Bezeichnung*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="build_street" name="build_street">
                    <label class="mdl-textfield__label" for="build_street">Strasse*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="number" id="build_number" name="build_number">
                    <label class="mdl-textfield__label" for="build_number">Nummer*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="number" id="build_plz" name="build_plz">
                    <label class="mdl-textfield__label" for="build_plz">PLZ*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="build_ort" name="build_ort">
                    <label class="mdl-textfield__label" for="build_ort">Ort*</label>
                </div>
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="gebaeude">
                    Erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-cell mdl-card-form mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Neues Stockwerk</h2>
        </div>
        <form action="/admin/add_floor" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="floor_name" name="floor_name">
                    <label class="mdl-textfield__label" for="floor_name">Bezeichnung*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="floor_number" name="floor_number">
                    <label class="mdl-textfield__label" for="floor_number">Ganzzahlige Nummer*</label>
                </div>
                <select class="gebaeude_select" name="gebaeude_select"
                        id="gebaeude_select">
                    <?php
                    echo '<option value="">Gebäude wählen..*</option>';
                    foreach ($gebaeude as $row) {
                        if (!empty($_POST['gebaeude_select']) && rawurldecode($_POST['gebaeude_select']) == $row->id) {
                            echo '<option class="" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->bezeichnung . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($row->id) . '">' . $row->bezeichnung . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="stockwerk">
                    Erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-cell mdl-card mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Neues Zimmer</h2>
        </div>
        <form action="/admin/add_room" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="room_name" name="room_name">
                    <label class="mdl-textfield__label" for="room_name">Bezeichnung*</label>
                </div>
                <select class="room_gebaeude_select" name="room_gebaeude_select"
                        id="room_gebaeude_select">
                    <?php
                    echo '<option value="">Gebäude wählen..*</option>';
                    foreach ($gebaeude as $row) {
                        if (!empty($_POST['room_gebaeude_select']) && rawurldecode($_POST['room_gebaeude_select']) == $row->id) {
                            echo '<option class="" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->bezeichnung . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($row->id) . '">' . $row->bezeichnung . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="zimmer">
                    Weiter
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-cell mdl-cell--12-col mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Gebäude</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="build_table">
            <thead>
            <tr>
                <th class="build_table">Bezeichnung</th>
                <th class="build_table">Strasse</th>
                <th class="build_table">Nummer</th>
                <th class="build_table">PLZ</th>
                <th class="build_table">Ort</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($gebaeude as $row) {
                echo '
          <tr data-id="' . $row->id . '">
          <td>' . $row->bezeichnung . '</td>
          <td>' . $row->strasse . '</td>
          <td>' . $row->nr . '</td>
          <td>' . $row->plz . '</td>
          <td>' . $row->ort . '</td>
          </tr>
          ';
            }
            ?>
            </tbody>
        </table>
        <div class="mdl-card__actions mdl-card--border">
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
                    id="delete_build_button">
                Gebäude Löschen
            </button>

            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_build_button').click(function (e) {

                        e.preventDefault();

                        var selectedBuilds = [];

                        $('table#build_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedBuilds.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedBuilds.length != 0) {
                            $.post("/admin/delete_selected_buildings", {buildings: selectedBuilds})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Gebäude erfolgreich gelöscht.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens ein Gebäude wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }

                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>
    <div class="mdl-card mdl-cell mdl-cell--7-col mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-cell mdl-cell--12-col mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Zimmer</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="room_table">
            <thead>
            <tr>
                <th class="room_table">Bezeichnung</th>
                <th class="room_table">Optionale Bezeichnung</th>
                <th class="room_table">Stockwerk</th>
                <th class="room_table">Gebäude</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($zimmer as $row) {
                echo '
          <tr data-id="' . $row->id . '">
          <td>' . $row->bezeichnung . '</td>
          <td>' . $row->optional_text . '</td>
          <td>';
                $buildID = 0;
                foreach ($stockwerke as $stockwerk) {
                    if ($stockwerk->id == $row->stockwerk_id) {
                        $buildID = $stockwerk->gebaeude_id;
                        echo $stockwerk->bezeichnung;
                    }
                }
                echo '</td>
          <td>';
                foreach ($gebaeude as $build) {
                    if ($build->id == $buildID) {
                        echo $build->bezeichnung;
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
                    id="delete_room_button">
                Zimmer Löschen
            </button>

            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_room_button').click(function (e) {

                        e.preventDefault();

                        var selectedRooms = [];

                        $('table#room_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedRooms.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedRooms.length != 0) {
                            $.post("/admin/delete_selected_rooms", {rooms: selectedRooms})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Zimmer erfolgreich gelöscht.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens ein Zimmer wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }

                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>
    <div class="mdl-card mdl-cell mdl-cell--5-col mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-cell mdl-cell--12-col mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Stockwerk</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="floor_table">
            <thead>
            <tr>
                <th class="floor_table">Bezeichnung</th>
                <th class="floor_table">Ganzzahl</th>
                <th class="floor_table">Gebäude</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($stockwerke as $row) {
                echo '
          <tr data-id="' . $row->id . '">
          <td>' . $row->bezeichnung . '</td>
          <td>' . $row->nummer . '</td>
          <td>';
                foreach ($gebaeude as $build) {
                    if ($build->id == $row->gebaeude_id) {
                        echo $build->bezeichnung;
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
                    id="delete_floor_button">
                Stockwerke Löschen
            </button>

            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_floor_button').click(function (e) {

                        e.preventDefault();

                        var selectedFloors = [];

                        $('table#floor_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedFloors.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedFloors.length != 0) {
                            $.post("/admin/delete_selected_floors", {floors: selectedFloors})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Stockwerke erfolgreich gelöscht.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens ein Stockwerk wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }

                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>
</div>