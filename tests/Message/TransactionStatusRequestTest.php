<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\IcepayPayments\AbstractTestCase;

/**
 * Class TransactionStatusRequestTest.
 */
class TransactionStatusRequestTest extends AbstractTestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Creates a new TransactionStatusRequestTest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new TransactionStatusRequest($this->httpClient, $this->httpRequest);
        $this->request->setBaseUrl('https://www.superbrave.nl');
        $this->request->setSecretKey('NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4');
        $this->request->setContractProfileId('64eb3717-8b5d-4088-8108-93224675e538');
        $this->request->setTransactionReference('e7ca29c8-f1f4-4a4c-a968-0f9667d0519d');
    }

    /**
     * Tests if TransactionStatusRequestTest::getData validates the basic keys and returns an array of data.
     */
    public function testGetData(): void
    {
        $expectedData = [
            'ContractProfileId' => '64eb3717-8b5d-4088-8108-93224675e538',
        ];
        $this->assertEquals($expectedData, $this->request->getData());
    }

    /**
     * Tests if TransactionStatusRequest::sendData returns a TransactionStatusResponse.
     */
    public function testSendData(): void
    {
        $data = [
            'AmountInCents' => 1337,
            'CurrencyCode' => 'EUR',
            'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
            'Timestamp' => '2019-03-09T12:00:00Z',
        ];
        $response = $this->request->sendData($data);

        $this->assertInstanceOf(TransactionStatusResponse::class, $response);

        $expectedRequest = new Request(
            AbstractRequest::METHOD_POST,
            'https://www.superbrave.nl/transaction/e7ca29c8-f1f4-4a4c-a968-0f9667d0519d'
        );

        $this->assertEquals($expectedRequest->getMethod(), $this->clientMock->getLastRequest()->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $this->clientMock->getLastRequest()->getUri());
    }
}
