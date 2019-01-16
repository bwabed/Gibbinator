<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Gebäude</h2>
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
            <div class="mdl-card__actions">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="gebaeude">
                    Erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-card-form mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Stockwerk</h2>
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
            <div class="mdl-card__actions">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="stockwerk">
                    Erstellen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-card-form mdl-cell--4-col mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Zimmer</h2>
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
            <div class="mdl-card__actions">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="zimmer">
                    Weiter
                </button>
            </div>
        </form>
    </div>
</div>