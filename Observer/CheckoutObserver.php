<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Interceptor;
use Psr\Log\LoggerInterface;
use Recommend\ReferralNetwork\Helper\ReferralSettingsData;
use Recommend\ReferralNetwork\Model\RecommendReferralSession;
use Recommend\ReferralNetwork\Service\RecommendReferralService;
use Recommend\ReferralNetwork\Value\RecommendReferral;

class CheckoutObserver implements ObserverInterface
{
    private $session;
    private $referralSettings;
    private $referralService;
    protected $logger;

    public function __construct(
        RecommendReferralSession $session,
        ReferralSettingsData     $referralSettings,
        RecommendReferralService $referralService,
        LoggerInterface          $logger
    ) {
        $this->session = $session;
        $this->referralSettings = $referralSettings;
        $this->referralService = $referralService;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var Interceptor $order */
        $order = $observer->getData('order');

        $orderId = $order->getId();

        // check if referral exists
        $referralData = $this->session->getReferralData();
        $this->logger->debug("Recommend referral data is present", [
            'referralData' => $referralData,
            'orderId'      => $orderId,
        ]);

        if (empty($referralData) || empty($referralData['code'])) {
            // nothing to do referral code not present
            $this->logger->debug("Recommend referral data is present code is missing");
            return;
        }

        $referralCode = $referralData['code'];
        $ssnid = $referralData['ssnid'];

        $this->logger->debug("Recommend referral is present in the session", [
            'referralCode' => $referralCode,
            'ssnid'        => $ssnid,
            'orderId'      => $orderId,
        ]);

        // check if Recommend referral integration is enabled
        $integrationEnabled = $this->referralSettings->isEnabled();
        if ($integrationEnabled === false) {
            // integration is disabled
            $this->logger->debug("Recommend referral is disabled in settings", [
                'referralCode' => $referralCode,
                'ssnid'        => $ssnid,
                'orderId'      => $orderId,
            ]);
            return;
        }

        $referral = new RecommendReferral(
            $orderId,
            $order->getIncrementId(),
            $order->getGrandTotal(),
            $order->getOrderCurrencyCode(),
            $referralCode,
            $ssnid,
            $order->getCustomerEmail()
        );

        try {
            $this->logger->debug("Sending referral to the Recommend", [
                'referralCode' => $referral->getCode(),
                'ssnid'        => $referral->getSsnid(),
                'orderId'      => $referral->getOrderId(),
                'orderNumber'  => $referral->getOrderNumber(),
                'grandTotal'   => $referral->getOrderTotal(),
                'currencyCode' => $referral->getCurrencyCode(),
            ]);
            $conversionId = $this->referralService->registerReferral($referral);
            $this->logger->info(
                sprintf('Successfully registered referral. Code: %s for order ID %s. Conversion ID %s', $referralCode, $orderId, $conversionId),
                [
                    'referralCode' => $referral->getCode(),
                    'ssnid'        => $referral->getSsnid(),
                    'conversionId' => $conversionId,
                    'orderId'      => $referral->getOrderId(),
                    'orderNumber'  => $referral->getOrderNumber(),
                    'grandTotal'   => $referral->getOrderTotal(),
                    'currencyCode' => $referral->getCurrencyCode(),
                ]
            );
            $this->session->clearReferralData();
            $this->logger->info('Referral code cleared from session', [
                'referralCode' => $referral->getCode(),
                'ssnid'        => $referral->getSsnid(),
            ]);
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('Failed to send Recommend referral. Code: %s for order ID %s', $referralCode, $orderId),
                [
                    'exception'    => $e,
                    'referralCode' => $referral->getCode(),
                    'ssnid'        => $referral->getSsnid(),
                    'orderId'      => $referral->getOrderId(),
                    'orderNumber'  => $referral->getOrderNumber(),
                    'grandTotal'   => $referral->getOrderTotal(),
                    'currencyCode' => $referral->getCurrencyCode(),
                ]
            );
        }
    }
}
