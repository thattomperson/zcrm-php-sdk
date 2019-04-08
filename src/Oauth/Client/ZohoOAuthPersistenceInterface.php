<?php

namespace Zoho\CRM\Oauth\Client;

interface ZohoOAuthPersistenceInterface
{
    public function saveOAuthData($zohoOAuthTokens);

    public function getOAuthTokens($userEmailId);

    public function deleteOAuthTokens($userEmailId);
}
