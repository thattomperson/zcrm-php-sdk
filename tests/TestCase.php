<?php

namespace Zoho\CRM\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Zoho\CRM\Library\Setup\Restclient\ZCRMRestClient;
use Zoho\CRM\Oauth\Client\ZohoOAuth;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        $a = $_ENV['GITHUB_TOKEN'];
        ZCRMRestClient::initialize([
            'client_id'              => $_ENV['CLIENT_ID'],
            'client_secret'          => $_ENV['CLIENT_SECRET'],
            'redirect_uri'           => 'https://localhost:8080',
            'currentUserEmail'       => $_ENV['CURRENT_USER_EMAIL'],
            'token_persistence_path' => __DIR__,
        ]);

        // $url = ZohoOAuth::getGrantURL() . "?" . implode('&', [
        //     'scope=ZohoCRM.modules.all,ZohoCRM.settings.all,ZohoCRM.org.all,ZohoCRM.users.all,aaaserver.profile.read',
        //     'client_id=' . $_ENV['CLIENT_ID'],
        //     'response_type=code',
        //     'access_type=offline',
        //     'redirect_uri=https://localhost:8080',
        // ]);
        // var_dump($url);
        // die;

        // $userIdentifier = $_ENV['CURRENT_USER_EMAIL'];
        // $oAuthClient = ZohoOAuth::getClientInstance();
        // $oAuthClient->generateAccessToken("1000.dbc0e7f8a9ea1d11509914db3a40ca48.9e7555dea593550af4bda59f107b9533");

        // $contents = file_get_contents(__DIR__.'/zcrm_oauthtokens.txt');
        // $oAuthTokens = unserialize($contents);

        // $refreshToken = $oAuthTokens->getRefreshToken();
        // $oAuthClient->generateAccessTokenFromRefreshToken($refreshToken,$userIdentifier);

        // $grantToken = "1000.bd4c7848d355f64e400b5dcc1a12f4ab.a80b26d201729dc56210b32f89f55cb7";
        // $oAuthTokens = $oAuthClient->generateAccessToken($grantToken);
        // $accessToken = $oAuthTokens->getAccessToken();
        // $refreshToken = $oAuthTokens->getRefreshToken();
    }
}
