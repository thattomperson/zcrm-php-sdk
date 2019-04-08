<?php

namespace Zoho\CRM\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Zoho\CRM\Library\Setup\Restclient\ZCRMRestClient;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        ZCRMRestClient::initialize([
            'client_id'              => $_ENV['CLIENT_ID'],
            'client_secret'          => $_ENV['CLIENT_SECRET'],
            'redirect_uri'           => 'https://localhost:8080',
            'currentUserEmail'       => $_ENV['CURRENT_USER_EMAIL'],
            'token_persistence_path' => __DIR__,
        ]);
    }
}
