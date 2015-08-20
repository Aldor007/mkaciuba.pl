<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('see blog page with list');
$I->amOnPage('/blog');
$I->see('Następna');
$I->see('Kategorie');
$I->see('Tagi');

