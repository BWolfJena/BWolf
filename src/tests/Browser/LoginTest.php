<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use \Rainlab\User\Models\User;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;
    #use DatabaseTransactions;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginUser()
    {
        $emailName = 'john.doe';
        $emailDomain = 'uni-jena.de';
        $email = $emailName . '@' . $emailDomain;
        $password = 'topsecret';
        $passwordConfirmation = $password;

        DB::table('users')->where('email',$email)->delete();
        $user = \Rainlab\User\Models\User::create(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
            ]
        );
        $user->is_activated = 1;
        $user->save();

        $this->browse(function (Browser $browser) {

            $browser->resize(1920, 1080);

            $browser->visit('kursuebersicht')
                    # Verify the login button is visible
                    ->assertSeeIn('.top-menu .menu .right a.button[href="/anmelden"]','Anmelden')
                    # Verify the register button is visible
                    ->assertSeeIn('.top-menu .menu .right a.button[href="/registrieren"]','Registrieren')

                    # Click register
                    # ->click('.top-menu .menu .right a.button[href="/registrieren"]')
                    #Verify register form
                    # Email
                    # ->assertVisible('#registerEmail')
                    # Password
                    # ->assertVisible('#registerPassword')
                    #Input
                    # Email
                    # -> type('#registerEmail', 'john.doe')
                    # Password;
                    # -> type('#registerPassword', 'topsecret')
                    # Submit form
                    # -> click('button[type="submit"]')

                    # Click register
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
                     -> type('#userSigninPassword', 'topsecret')
                    # Submit form
                     -> click('button[type="submit"]')
                     -> waitForLocation('/kursuebersicht')
                     -> assertDontSeeIn('.top-menu .menu .right a.button','Anmelden');
        });
    }
}
