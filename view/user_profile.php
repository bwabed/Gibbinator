<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 04.01.2019
 * Time: 17:58
 */
?>
<div class="mdl-cell mdl-card mdl-card-form mdl-shadow--2dp mdl-cell--12-col">
    <div class="mdl-card__title mdl-color--indigo-500">
        <h1 class="mdl-card__title-text mdl-color-text--white">Edit Profile</h1>
    </div>
    <div class="mdl-grid">
        <div class="mdl-card mdl-cell mdl-cell--1-offset mdl-cell--5-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card-form mdl-shadow--2dp">
            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text mdl-color-text--white">Passwort ändern</h2>
            </div>
            <div class="mdl-card__supporting-text mdl-grid">
                <form action="/user/check_changePassword/" method="post">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                        <input class="mdl-textfield__input" type="password" id="old_password" name="old_password">
                        <label class="mdl-textfield__label" for="old_password">Altes Passwort</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                        <input class="mdl-textfield__input" type="password" id="new_password" name="new_password"
                               pattern="(?=^.{8,}$)^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$"
                               oninput="check_password()">
                        <label class="mdl-textfield__label" for="new_password">Neues Passwort</label>
                        <span class="mdl-textfield__error">Include <span id="uppercase">UpperCase,</span> <span
                                    id="lowercase">LowerCase,</span> <span id="number">Nummer</span> und <span
                                    id="length">min 8 Zeichen,</span> bitte!</span>
                        <script>
                            function check_password() {
                                var uppercasePattern = new RegExp('[A-Z]');
                                var lowercasePattern = new RegExp('[a-z]');
                                var numberPattern = new RegExp('[0-9]');
                                var lengthPattern = new RegExp('.{8,}')
                                var passwordInput = document.getElementById('new_password');
                                var uppercase = document.getElementById('uppercase');
                                var lowercase = document.getElementById('lowercase');
                                var number = document.getElementById('number');
                                var length = document.getElementById('length');
                                if (passwordInput.value.match(uppercasePattern)) {
                                    uppercase.style.color = "green";
                                } else {
                                    uppercase.style.color = "red";
                                }
                                if (passwordInput.value.match(lowercasePattern)) {
                                    lowercase.style.color = "green";
                                } else {
                                    lowercase.style.color = "red";
                                }
                                if (passwordInput.value.match(numberPattern)) {
                                    number.style.color = "green";
                                } else {
                                    number.style.color = "red";
                                }
                                if (passwordInput.value.match(lengthPattern)) {
                                    length.style.color = "green";
                                } else {
                                    length.style.color = "red";
                                }
                            }
                        </script>
                    </div>
                    <div class="mdl-card__actions send-button">
                        <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button"
                                id="send">
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mdl-card mdl-cell mdl-cell--5-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card-form mdl-shadow--2dp">
            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text mdl-color-text--white">E-Mail ändern</h2>
            </div>
            <div class="mdl-card__supporting-text mdl-grid">
                <form action="/user/check_changeEmail/" method="post">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                        <input class="mdl-textfield__input" type="text" id="old_email" name="old_email">
                        <label class="mdl-textfield__label" for="old_email">Alte E-Mail</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                        <input class="mdl-textfield__input" type="text" id="new_email" name="new_email"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                        <label class="mdl-textfield__label" for="new_email">Neue E-Mail</label>
                        <span class="mdl-textfield__error">Gültige E-Mail, bitte!</span>
                    </div>
                    <div class="mdl-card__actions send-button">
                        <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button"
                                id="send">
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
