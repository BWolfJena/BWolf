<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
#use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends DuskTestCase
{
    #use RefreshDatabase;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $emailName = 'john.doe';
        $emailDomain = 'uni-jena.de';
        $email = $emailName . '@' . $emailDomain;
        $password = 'topsecret';
        $passwordConfirmation = $password;


        \Rainlab\User\Models\User::where('email', $email)->delete();
        \Rainlab\User\Models\User::create(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation
            ]
        );

        $this->browse(function (Browser $browser) {
            $browser->visit('kursuebersicht')
                    # Verify the login button is visible
                    ->assertSeeIn('.top-menu .menu .right a.button[href="/anmelden"]','Anmelden')
                    # Click login
                    ->click('.top-menu .menu .right a.button[href="/anmelden"]')
                    # Verify login form
                    # Email
                    ->assertVisible('#userSigninLogin')
                    # Password
                    ->assertVisible('#userSigninPassword')
                    #Input
                    # Email
                    -> type('#userSigninLogin', 'john.doe')
                    # Password;
                    -> type('#userSigninLogin', 'topsecret')
                    # Submit form
                    -> click('button[type="submit"]');
                    #->assertSeeIn('','Ergebnisübersicht')
        });



        \Rainlab\User\Models\User::where('email', $email)->delete();
    }
}
