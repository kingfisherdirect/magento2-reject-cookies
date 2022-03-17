<?php

namespace KingfisherDirect\RejectCookies\Plugin\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\GoogleAnalytics\Block\Ga;

class GaAddClientAndUserId
{
    private const SALT = "5bba03fd509905031baadcdc105362a1";

    private RemoteAddress $remoteAddress;

    private Header $httpHeader;

    private Session $session;

    public function __construct(
        Header $httpHeader,
        RemoteAddress $remoteAddress,
        Session $session
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        $this->session = $session;
    }

    public function afterGetPageTrackingData(Ga $subject, $result)
    {
        $result['clientId'] = hash('md5', $this->remoteAddress->getRemoteAddress() . $this->httpHeader->getHttpUserAgent() . $this->httpHeader->getHttpAcceptLanguage() . self::SALT);

        $result['userId'] = $this->session->getCustomerId()
            ? hash('md5', $this->session->getCustomerId() . self::SALT)
            : null;

        return $result;
    }
}
