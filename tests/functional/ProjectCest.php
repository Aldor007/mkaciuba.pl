<?php
use \FunctionalTester;

class ProjectCest
{
    public function _before(FunctionalTester $I)
    {
        /* $I->haveHttpHeader('Host', 'mkaciuba.dev'); */
        $I->amOnPage('/projekty');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function linkToProject(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('Projekty');
        $I->click('Projekty');
        $I->see('Technologie');
        $I->see('Czytaj więcej');
    }

    public function singleProjectPage(FunctionalTester $I) {

        $I->click('//*[@id="content"]/div[1]/div[2]/article[1]/div/header/h3/a');
        $I->seeInTitle('Projekty |');
        $I->dontSee('Powiązane posty');
        $I->seeNumberOfElements('img', [4, 5]);

    }
    public function technologyPage(FunctionalTester $I) {
        $I->click('//*[@id="content"]/div[2]/aside/div/ul/li[1]/a');
        $I->seeInTitle('| Projekty |');
        $I->see('Czytaj więcej');
    }

}
