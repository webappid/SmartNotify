<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-17
 * Time: 10:31
 */

namespace WebAppId\SmartNotify;

use GuzzleHttp\Client;

/**
 * Class SmartNotify
 * @package WebAppId\SmartNotify
 */
class SmartNotify
{

    public const SYSTEM_ERROR = 'SYSTEM_ERROR';

    public const WARNING = 'WARNING';

    public const PAYMENT_REPORT = 'PAYMENT_REPORT';

    public const PAYMENT_NOTIFY = 'PAYMENT_NOTIFY';

    public const BOOKING_NOTIFY = 'BOOKING_NOTIFY';

    public static function push(string $message, string $category = self::SYSTEM_ERROR)
    {
        $url = 'https://push.webappid.com/api/notify';
        $client = new Client(['cookies' => true, 'Referer' => $url, 'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36']);
        try {
            $client->post($url,
                [
                    'form_params' => [
                        'token' => env('NOTIFY_TOKEN'),
                        'messages' => $message,
                        'category' => $category
                    ]
                ]
            );
        } catch (\Exception $exception) {
            //nothing to report
        }
    }
}