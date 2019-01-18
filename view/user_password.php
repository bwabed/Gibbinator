<div class="mdl-grid">
    <div class="mdl-card mdl-cell mdl-cell--4-offset mdl-cell--4-col mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Change Password</h2>
        </div>
        <div class="mdl-card__supporting-text mdl-grid">
            <form action="/user/check_changePassword/" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="password" id="old_password" name="old_password">
                    <label class="mdl-textfield__label" for="old_password">Old Password</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="password" id="new_password" name="new_password"
                           pattern="(?=^.{8,}$)^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$"
                           oninput="check_password()">
                    <label class="mdl-textfield__label" for="new_password">New Password</label>
                    <span class="mdl-textfield__error">Include <span id="uppercase">UpperCase,</span> <span
                                id="lowercase">LowerCase,</span> <span id="number">Number</span> and <span
                                id="length">min 8 Characters,</span> please!</span>
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
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>