<div class="mdl-card mdl-cell mdl-cell--4-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--4-offset-desktop mdl-card-form mdl-shadow--2dp" id="loginCard">
    <div class="mdl-card__title">
        <h1 class="mdl-card__title-text">Login</h1>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <form action="/user/check_login" method="post">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                <input class="mdl-textfield__input" type="email" id="login_username" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars(strip_tags($_POST['username'])) : ''; ?>">
                <label class="mdl-textfield__label" for="login_username">Email</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                <input class="mdl-textfield__input" type="password" id="login_password" name="password">
                <label class="mdl-textfield__label" for="login_password">Passwort</label>
            </div>
            <div class="mdl-card__actions send-button">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button" id="send">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>