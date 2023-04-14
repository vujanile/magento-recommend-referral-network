<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Model;

class RecommendReferralSession
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    public function setReferral($referral, $ssnid)
    {
        $this->session->setRecommendRef([
            'code'  => $referral,
            'ssnid' => $ssnid,
        ]);
    }

    public function getReferralData()
    {
        return $this->session->getRecommendRef();
    }

    public function clearReferralData()
    {
        $this->session->unsRecommendRef();
    }
}
