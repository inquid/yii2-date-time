<p align="center">
    <a href="http://www.yiiframework.com/" target="_blank">
        <img src="http://static.yiiframework.com/files/logo/yii.png" width="400" alt="Yii Framework" />
    </a>
</p>

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=contact@inquid.co&item_name=Yii2+extensions+support&item_number=22+Campaign&amount=5%2e00&currency_code=USD)

Yii2 Datetime Handler
=====================
Date time handler for yii2


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist inquid/yii2-date-time-handler "*"
```

or add

```
"inquid/yii2-date-time-handler": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use the methods you need as the following:

```php
\inquid\date_time\DateTimeHandler::formatDate($date,'Y-m-d');

\inquid\date_time\DateTimeHandler::now();

\inquid\date_time\DateTimeHandler::getWeekNumberByDate('2018-09-09');
```

And thats it!

SUPPORT
-----
[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=contact@inquid.co&item_name=Yii2+extensions+support&item_number=22+Campaign&amount=5%2e00&currency_code=USD)
