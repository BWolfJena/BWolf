<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomepageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            # Resize browser so we have a desktop view
            $browser->resize(1920, 1080);

            
            $browser->visit('')
                    # Verify left header
                    ->assertSeeIn('.top-menu .menu a:nth-child(3)','Start')
                    ->assertSeeIn('.top-menu .menu a:nth-child(4)','Kursübersicht')
                    ->assertSeeIn('.top-menu .menu a:nth-child(5)','Meine Präferenzliste')
                    ->assertSeeIn('.top-menu .menu a:nth-child(6)','Ergebnis sehen')
                    # Verify right header
                    ->assertSeeIn('.top-menu .right-nav a.button:nth-child(1)','Anmelden')
                    ->assertSeeIn('.top-menu .right-nav a.button:nth-child(2)','Registrieren')
                    #Verify footer
                    ;


        });
    }
}
