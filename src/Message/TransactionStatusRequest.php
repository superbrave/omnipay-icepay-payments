<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The request for getting the transaction status at Icepay.
 */
class TransactionStatusRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['ContractProfileId'] = $this->getContractProfileId();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        $transactionStatusResponse = $this->getTransactionStatusFromPostBack();

        if ($transactionStatusResponse !== null) {
            return $transactionStatusResponse;
        }

        $this->sendRequest(
            Request::METHOD_POST,
            sprintf(
                '/transaction/%s',
                $this->getTransactionReference()
            ),
            $data
        );

        return new TransactionStatusResponse(
            $this,
            $this->getResponseBody(),
            $this->getResponse()->getStatusCode()
        );
    }

    /**
     * Use the data sent by Icepay in the post back to check the status.
     * This is necessary because Icepay has a delay in their backend if you request the status immediately after the signal.
     *
     * @see http://docs2.icepay.com/payment-process/handling-the-postback/postback-sample/
     *
     * @return TransactionStatusResponse|null - Null when the data is is not sent or not correct
     */
    private function getTransactionStatusFromPostBack(): ?TransactionStatusResponse
    {
        if (stripos($this->httpRequest->getContentType(), 'json') === false) {
            return null;
        }

        try {
            $content = $this->httpRequest->getContent();
            $contentAsArray = json_decode($content, true);
            $contentAsStdObj = json_decode($content);
        } catch (\LogicException $exception) {
            return null;
        }

        if (is_array($contentAsArray) === false || isset($contentAsArray['StatusCode']) === false) {
            return null;
        }

        $this->setContractProfileId($contentAsStdObj->ContractProfileId);

        if ($this->validateSecurityHashMatch($this->httpRequest, $contentAsStdObj) === false) {
            return null;
        }

        $camelCasedKeysContent = array_combine(
            array_map('lcfirst', array_keys($contentAsArray)),
            array_values($contentAsArray)
        );

        return new TransactionStatusResponse(
            $this,
            $camelCasedKeysContent,
            200
        );
    }

    /**
     * Get the security hash from the request and match it against a generated hash from the sent values.
     * Will throw an exception if it does not match.
     * Needs the POSTed Json as a php array.
     *
     * @param Request   $request
     * @param \stdClass $contentAsStdObj
     *
     * @return bool
     */
    private function validateSecurityHashMatch(Request $request, \stdClass $contentAsStdObj): bool
    {
        $sentSecurityHash = $request->headers->get('checksum');

        $possibleHashes = $this->getPossibleValidHashes($request, $contentAsStdObj);

        foreach ($possibleHashes as $generatedHash) {
            if ($generatedHash === $sentSecurityHash) {
                return true;
            }
        }

        return false;
    }

    /**
     * They way Icepay generates the hash is by using our notification url.
     * Though, they might add a trailing slash. Unsure of this at this point, so check both.
     *
     * @param Request   $request
     * @param \stdClass $contentAsStdObj
     *
     * @return array
     */
    private function getPossibleValidHashes(Request $request, $contentAsStdObj): array
    {
        $notifyUrls = [
            $request->getSchemeAndHttpHost().$request->getRequestUri(),
            $request->getSchemeAndHttpHost().$request->getRequestUri().'/',
        ];

        $hashes = [];
        foreach ($notifyUrls as $notifyUrl) {
            $hashes[] = $this->getSecurityHash(
                $request->getMethod(),
                $notifyUrl,
                $contentAsStdObj,
                true
            );
        }

        return $hashes;
    }
}
