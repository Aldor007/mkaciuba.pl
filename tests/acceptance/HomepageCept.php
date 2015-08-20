<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('see home pag with menu and carousel');
$I->amOnPage('/');
$I->seeLink('Home');
$I->seeLink('Blog');
$I->seeLink('Projekty');
$I->seeLink('Fotografie');
$I->seeElement('div.carousel-inner');
