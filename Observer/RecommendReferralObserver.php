<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Recommend\ReferralNetwork\Model\RecommendReferralSession;

class RecommendReferralObserver implements ObserverInterface
{
    private const REF_KEY = "rcmndref";
    private const SSNID_KEY = "ssnid";

    private $session;
    private $logger;

    public function __construct(
        RecommendReferralSession $session,
        LoggerInterface          $logger
    ) {
        $this->session = $session;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var Http $request */
        $request = $observer->getEvent()->getData("request");

        // Skip Ajax and non GET requests
        if ($request->isAjax() || !$request->isGet()) {
            return;
        }

        // Check if Recommend ref url param exists in the url and save it to the session
        $query = $request->getQuery();
        $referralCode = $query->get(self::REF_KEY);
        $ssnid = $query->get(self::SSNID_KEY);
        if ($referralCode) {
            $this->logger->debug("Recommend referral code is present in the query params", [
                'referralCode' => $referralCode,
                'ssnid'        => $ssnid,
            ]);
            $this->session->setReferral($referralCode, $ssnid);
            $this->logger->debug("Recommend referral code set to session", [
                'referralCode' => $referralCode,
                'ssnid'        => $ssnid,
            ]);
        }
    }
}
