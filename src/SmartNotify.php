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
    public static function push(string $message)
    {
        $url = 'https://push.webappid.com/api/notify';
        $client = new Client(['cookies' => true, 'Referer' => $url, 'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36']);
        try {
            $client->post($url,
                [
                    'form_params' => [
                        'token' => env('NOTIFY_TOKEN'),
                        'messages' => $message
                    ]
                ]
            );
        } catch (\Exception $exception) {
            //nothing to report
        }
    }
}