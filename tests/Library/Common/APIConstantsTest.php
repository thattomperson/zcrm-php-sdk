<?php namespace Zoho\CRM\Tests\Library\Common;

use PHPUnit\Framework\TestCase;

use Zoho\CRM\Library\Common\APIConstants;

class APIConstantsTest extends TestCase
{

    public function testConstants()
    {  
        $this->assertEquals('error',APIConstants::ERROR);
        $this->assertEquals('GET',APIConstants::REQUEST_METHOD_GET);
        $this->assertEquals('PUT',APIConstants::REQUEST_METHOD_PUT);
        $this->assertEquals('POST',APIConstants::REQUEST_METHOD_POST);
        $this->assertEquals('DELETE',APIConstants::REQUEST_METHOD_DELETE);
        $this->assertEquals('Zoho-oauthtoken ',APIConstants::OAUTH_HEADER_PREFIX);
        $this->assertEquals('Authorization',APIConstants::AUTHORIZATION);
        $this->assertEquals('api_name',APIConstants::API_NAME);
        $this->assertEquals('The given id seems to be invalid.',APIConstants::INVALID_ID_MSG);
        $this->assertEquals('Cannot process more than 100 records at a time.',APIConstants::API_MAX_RECORDS_MSG);
        $this->assertEquals('INVALID_DATA',APIConstants::INVALID_DATA);
        $this->assertEquals('error',APIConstants::STATUS_ERROR);
        $this->assertEquals('success',APIConstants::STATUS_SUCCESS);
        $this->assertEquals('Leads',APIConstants::LEADS);
        $this->assertEquals('Accounts',APIConstants::ACCOUNTS);
        $this->assertEquals('Contacts',APIConstants::CONTACTS);
        $this->assertEquals('Deals',APIConstants::DEALS);
        $this->assertEquals('Quotes',APIConstants::QUOTES);
        $this->assertEquals('SalesOrders',APIConstants::SALESORDERS);
        $this->assertEquals('Invoices',APIConstants::INVOICES);
        $this->assertEquals('PurchaseOrders',APIConstants::PURCHASEORDERS);
        $this->assertEquals('per_page',APIConstants::PER_PAGE);
        $this->assertEquals('page',APIConstants::PAGE);
        $this->assertEquals('count',APIConstants::COUNT);
        $this->assertEquals('more_records',APIConstants::MORE_RECORDS);
        $this->assertEquals('message',APIConstants::MESSAGE);
        $this->assertEquals('code',APIConstants::CODE);
        $this->assertEquals('status',APIConstants::STATUS);
        
        $this->assertEquals('data',APIConstants::DATA);
        $this->assertEquals('info',APIConstants::INFO);
        $this->assertEquals(200,APIConstants::RESPONSECODE_OK);
        $this->assertEquals(201,APIConstants::RESPONSECODE_CREATED);
        $this->assertEquals(202,APIConstants::RESPONSECODE_ACCEPTED);
        $this->assertEquals(204,APIConstants::RESPONSECODE_NO_CONTENT);
        $this->assertEquals(301,APIConstants::RESPONSECODE_MOVED_PERMANENTLY);
        $this->assertEquals(302,APIConstants::RESPONSECODE_MOVED_TEMPORARILY);
        $this->assertEquals(304,APIConstants::RESPONSECODE_NOT_MODIFIED);
        $this->assertEquals(400,APIConstants::RESPONSECODE_BAD_REQUEST);
        $this->assertEquals(401,APIConstants::RESPONSECODE_AUTHORIZATION_ERROR);
        $this->assertEquals(403,APIConstants::RESPONSECODE_FORBIDDEN);
        $this->assertEquals(404,APIConstants::RESPONSECODE_NOT_FOUND);
        $this->assertEquals(405,APIConstants::RESPONSECODE_METHOD_NOT_ALLOWED);
        $this->assertEquals(413,APIConstants::RESPONSECODE_REQUEST_ENTITY_TOO_LARGE);
        $this->assertEquals(415,APIConstants::RESPONSECODE_UNSUPPORTED_MEDIA_TYPE);
        $this->assertEquals(429,APIConstants::RESPONSECODE_TOO_MANY_REQUEST);
        $this->assertEquals(500,APIConstants::RESPONSECODE_INTERNAL_SERVER_ERROR);
        $this->assertEquals("../../../../../../resources",APIConstants::DOWNLOAD_FILE_PATH);
        
    }
    
}
?>