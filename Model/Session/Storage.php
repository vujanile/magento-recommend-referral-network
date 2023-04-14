<?php

namespace Recommend\ReferralNetwork\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{
    public function __construct(
        $namespace = 'recommend_referral_network',
        array $data = []
    ) {
        parent::__construct($namespace, $data);
    }
}
