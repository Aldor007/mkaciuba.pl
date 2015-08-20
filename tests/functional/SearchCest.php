<?php
use \FunctionalTester;

class SearchCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function searchString(FunctionalTester $I) {
        $I->submitForm('//*[@id="header"]/div/div[2]/div/form', ['search' =>  'mkaciuba']);
        $I->seeResponseCodeIs(200);
        $I->see('dla "mkaciuba"');
        $I->see('Posty');
        $I->see('Projekty');
        $I->see('Kategorie fotografii');
        $I->see('Fotografie');
    }
}
