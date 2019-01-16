<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--3-offset-desktop mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title">
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/room_detail" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="room_opt" name="room_opt">
                    <label class="mdl-textfield__label" for="room_opt">Zusätzliche Bez.</label>
                </div>
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
            </form>
        </div>
    </div>
</div>