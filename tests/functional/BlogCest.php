<?php
use \FunctionalTester;

class BlogCest
{
    public function _before(FunctionalTester $I)
    {
        /* $I->haveHttpHeader('Host', 'mkaciuba.dev'); */
        $I->amOnPage('/blog');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function linkToBlog(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('Blog');
        $I->click('Blog');
        $I->see('Następna');
        $I->see('Kategorie');
        $I->see('Tagi');
        $I->see('Czytaj więcej');
    }

    public function singlePostPage(FunctionalTester $I) {

        $I->click('//*[@id="content"]/div[1]/div[2]/article[1]/header/h3/a');
        $I->seeInTitle('Blog |');
        $I->see('Powiązane posty');
        $I->seeNumberOfElements('img', [4, 12]);

    }
    public function categoryPage(FunctionalTester $I) {
        $I->click('//*[@id="content"]/div[3]/aside[1]/div/ul/li[1]/a');
        $I->seeInTitle('Blog |');
        $I->see('Czytaj więcej');
    }

    public function tagsPage(FunctionalTester $I) {
        $I->click('//*[@id="content"]/div[3]/aside[2]/div/div/a[1]');
        $I->seeInTitle('Tag');
    }

    public function archive(FunctionalTester $I) {
        $I->amOnPage('/blog/2015/05');
        $I->seeResponseCodeIs(200);
        $I->see('Czytaj więcej');
    }
}
