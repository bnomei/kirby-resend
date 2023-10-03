<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Resend;
use Resend\Resend as ResendClient;
use PHPUnit\Framework\TestCase;

final class ResendTest extends TestCase
{
    /** @var array */
    private $bot;

    private function needsAPI(): void
    {
        if (!file_exists(__DIR__ . '/site/config/config.php')) {
            $this->markTestSkipped('No config file with API-Key.');
        }
    }

    public function setUp(): void
    {
        $this->bot = ['bot' => true];
    }

    public function testResendLibExists()
    {
        $this->assertIsString(ResendClient::class);
    }

    public function testConstruct()
    {
        $resend = new Resend();

        $this->assertInstanceOf(Resend::class, $resend);
    }

    public function testClient()
    {
        $resend = new Resend();

        $this->assertInstanceOf(ResendClient::class, $resend->client());
    }

    public function testSingleton()
    {
        // static instance does not exists
        $resend = Bnomei\Resend::singleton();
        $this->assertInstanceOf(Resend::class, $resend);

        // static instance now does exist
        $resend = Bnomei\Resend::singleton();
        $this->assertInstanceOf(Resend::class, $resend);
    }

    public function testCallableOptions()
    {
        $resend = new Resend([
            'apikey' => function () {
                return 'APIKEY';
            },
        ]);

        $this->assertInstanceOf(Resend::class, $resend);
    }

    public function testSMTPTransportOptions()
    {
        $resend = new Resend([
            'apikey' => 'APIKEY',
        ]);
        $smtpTO = $resend->transport();

        $this->assertEquals('smtp', $smtpTO['type']);
        $this->assertEquals('smtp.resend.com', $smtpTO['host']);
        $this->assertEquals(587, $smtpTO['port']);
        $this->assertEquals('tsl', $smtpTO['security']);
        $this->assertEquals(true, $smtpTO['auth']);
        $this->assertEquals('resend', $smtpTO['username']);
        $this->assertEquals('APIKEY', $smtpTO['password']);
    }
}
