<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ReferralSettingsData extends AbstractHelper
{
    const XML_PATH_REFERRAL_SETTINGS = 'recommend_referral_network_settings/';

    const SETTING_IS_ENABLED = "enabled";
    const SETTING_TEST_URL = "test_url";
    const SETTING_API_TOKEN = "api_token";

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled()
    {
        $enabled = $this->getConfigValue(self::XML_PATH_REFERRAL_SETTINGS . 'general/' . self::SETTING_IS_ENABLED, null);

        return $enabled === "1";
    }

    public function getTestUrl()
    {
        return $this->getConfigValue(self::XML_PATH_REFERRAL_SETTINGS . 'general/' . self::SETTING_TEST_URL, null);
    }

    public function getApiToken()
    {
        return $this->getConfigValue(self::XML_PATH_REFERRAL_SETTINGS . 'general/' . self::SETTING_API_TOKEN, null);
    }

}
