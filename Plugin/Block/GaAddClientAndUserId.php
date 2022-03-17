<?php

namespace KingfisherDirect\RejectCookies\Plugin\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\GoogleAnalytics\Block\Ga;

class GaAddClientAndUserId
{
    private RemoteAddress $remoteAddress;

    private Header $httpHeader;

    private Session $session;

    private EncryptorInterface $encryptor;

    public function __construct(
        Header $httpHeader,
        RemoteAddress $remoteAddress,
        Session $session,
        EncryptorInterface $encryptor
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        $this->session = $session;
        $this->encryptor = $encryptor;
    }

    public function afterGetPageTrackingData(Ga $subject, $result)
    {
        $result['clientId'] = $this->encryptor->hash($this->remoteAddress->getRemoteAddress() . $this->httpHeader->getHttpUserAgent() . $this->httpHeader->getHttpAcceptLanguage());

        $result['userId'] = $this->session->getCustomerId()
            ? $this->encryptor->hash($this->session->getCustomerId())
            : null;

        return $result;
    }
}
