<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{

    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::factory()->create();

        Artisan::call('db:seed', ['--class' => 'ProductSeeder']);
    }

    protected function tearDown(): void
    {
        // parent::tearDown();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            // --disable-gpu,
            // headless
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
            ->with('.special-text', function ($text) {
                $text->assertSee('title1');
            });

            // 確認商品數量
            $browser->click('.check_product')
                ->waitForDialog(5)
                ->assertDialogOpened('商品數量充足')
                ->acceptDialog();

            // 設定中斷點
            eval(\Psy\sh());
        });
    }

}
