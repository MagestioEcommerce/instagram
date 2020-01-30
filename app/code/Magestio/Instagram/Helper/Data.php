<?php

namespace Magestio\Instagram\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH_ACTIVE       = 'instagram/general/active';
    const XML_PATH_ACCESS_TOKEN = 'instagram/general/access_token';
    const XML_PATH_AMOUNT       = 'instagram/general/amount';

    /**
     * If enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_AMOUNT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Access Token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ACCESS_TOKEN, ScopeInterface::SCOPE_STORE);
    }

}
