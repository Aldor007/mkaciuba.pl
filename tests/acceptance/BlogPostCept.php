<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('see blog post');
$I->amOnPage('/blog');
$I->click('Czytaj więcej');
$I->see('Powiązane posty');
$I->see('w');
