<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Service;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Magento\Framework\Module\ModuleListInterface;
use Recommend\ReferralNetwork\Error\RegisterReferralException;
use Recommend\ReferralNetwork\Helper\ReferralSettingsData;
use Recommend\ReferralNetwork\Value\RecommendReferral;

class RecommendReferralService
{
    const MODULE_NAME = 'Recommend_ReferralNetwork';

    private $referralSettings;
    private $moduleList;

    public function __construct(
        ReferralSettingsData $referralSettings,
        ModuleListInterface  $moduleList
    ) {
        $this->referralSettings = $referralSettings;
        $this->moduleList = $moduleList;
    }

    /**
     * @param $referral RecommendReferral
     *
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws RegisterReferralException
     */
    public function registerReferral($referral)
    {
        $client = $this->getClient();
        $apiToken = $this->referralSettings->getApiToken();
        $testUrl = $this->referralSettings->getTestUrl();

        $payload = [
            'code'        => $referral->getCode(),
            'orderNumber' => $referral->getOrderNumber(),
            'email'       => $referral->getEmail(),
            'cartTotal'   => sprintf('%s %s', $referral->getOrderTotal(), $referral->getCurrencyCode()),
            'ssnid'       => $referral->getSsnid(),
            'apiToken'    => $apiToken,
        ];

        $response = $client->post($testUrl, [
            RequestOptions::HEADERS => [
                'User-Agent' => $this->getUserAgent(),
            ],
            RequestOptions::JSON    => $payload,
        ]);

        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
            if (!empty($responseData['conversionId'])) {
                return $responseData['conversionId'];
            }
        }

        throw new RegisterReferralException('Failed to get conversion ID from Recommend referral service');
    }

    private function getClient()
    {
        return new Client();
    }

    private function getUserAgent()
    {
        return sprintf('Recommend Magento %s', $this->getVersion());
    }

    private function getVersion()
    {
        return $this->moduleList->getOne(self::MODULE_NAME)['setup_version'];
    }
}
