<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--3-offset-desktop mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/edit_room" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="room_opt" name="room_opt">
                    <label class="mdl-textfield__label" for="room_opt">Zusätzliche Bez.</label>
                </div>
                <input type="hidden" id="room_name" name="room_name" value="<?= $roomData['name'] ?>">
                <input type="hidden" id="gebaeude_id" name="gebaeude_id" value="<?= $roomData['gebaeude_id'] ?>">
                <select class="floor_select" name="floor_select"
                        id="floor_select">
                    <?php
                    echo '<option value="">Stockwerk wählen..*</option>';
                    foreach ($floors as $row) {
                        if (!empty($_POST['floor_select']) && rawurldecode($_POST['floor_select']) == $row->id) {
                            echo '<option class="" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->bezeichnung . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($row->id) . '">' . $row->bezeichnung . '</option>';
                        }
                    }
                    ?>
                </select>
                <div class="mdl-card__actions">
                    <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                            id="zimmer">
                        Speichern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>