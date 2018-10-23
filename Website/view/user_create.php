<?php

$form = new Form('/user/doCreate');

echo $form->text()->label('Vorname')->name('firstName')->value("Test");
echo $form->text()->label('Nachname')->name('lastName');
echo $form->text()->label('Mail')->name('email');
// echo $form->password()->label('Password')->name('password');
echo $form->submit()->label('Benutzer erstellen')->name('send');

$form->end();
