<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;
use Resend\Resend as ResendClient;

final class Resend
{
    /** @var ResendClient */
    private $client;

    /** @var array */
    private $options;

    public function __construct(array $options = [])
    {
        $defaults = [
            'debug' => option('debug'),
            // 'log' => option('bnomei.resend.log.fn'), // TODO: logging
            'username' => option('bnomei.resend.username'),
            'apikey' => option('bnomei.resend.apikey'),
            'trap' => option('bnomei.resend.trap'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options as $key => $callable) {
            if (is_callable($callable) && in_array($key, ['username', 'apikey'])) {
                $this->options[$key] = trim((string) $callable()) . '';
            }
        }

        $this->client = ResendClient::client(
            $this->options['apikey']
        );

        if ($this->option('debug')) {
            kirby()->cache('bnomei.resend')->flush();
        }
    }

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public function option(?string $key = null)
    {
        if ($key) {
            return A::get($this->options, $key);
        }
        return $this->options;
    }

    /**
     * Get Resend Client Instance
     *
     * @return ResendClient
     */
    public function client(): ResendClient
    {
        return $this->client;
    }

    /**
     * Get SMTP Email Transport Options Array
     *
     * @return array
     */
    public function transport(): array
    {
        return array_merge(
            [
                'username' => $this->option('username'),
                'password' => $this->option('apikey'),
            ],
            option('bnomei.resend.email.transport')
        );
    }

    public function trap(?string $email = null): ?string
    {
        return $email ?? $this->option('trap');
    }

    /** @var Resend */
    private static $singleton;

    /**
     * @param array $options
     * @return Resend
     */
    public static function singleton(array $options = [])
    {
        if (!self::$singleton) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
