
<?php
use \FunctionalTester;

class GalleryCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/galeria');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function gallery(FunctionalTester $I) {
        $I->seeInTitle('Fotografie | Galeria fotografii');
        $I->see('Portrety');
        $I->see('Sport');
        $I->see('Wydarzenia');
    }

    public function category(FunctionalTester $I) {
        $I->click('//*[@id="main"]/div/div[1]/a');
        $I->seeInTitle('Portrety');
    }
}
