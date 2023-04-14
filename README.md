# Recommend Referral Network - Magento 2 module

---

This library includes the core files of the Recommend Referral Network.
The directories hierarchy is as positioned in a standard magento 2 project library

---

## Requirements

Magento 2.3+ (Up to module verion 2.4.5)

## ✓ Install via [composer](https://getcomposer.org/download/) (recommended)

Run the following command under your Magento 2 root dir:

```
composer require recommend/magento-recommend-referral-network
php bin/magento maintenance:enable
php bin/magento module:enable Recommend_ReferralNetwork
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento maintenance:disable
php bin/magento cache:flush
```

## Usage

After you have installed Recommend Referral Network module,
you will have to connect to Recommend profile via the
API key.

Go to The Magento 2 admin panel

Go to Stores -> Configuration -> Recommend -> Referral Network Settings

Go to your Recommend profile and copy the API key and
Test URL and enter them in the form, set module enabled and click Save.

[www.recommend.co](https://www.recommend.co/)

Copyright © 2023 Recommend. All rights reserved.

![Recommend Logo](https://www.recommend.co/images/logo/recommend-logo.svg)
