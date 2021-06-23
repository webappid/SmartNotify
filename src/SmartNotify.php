<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-17
 * Time: 10:31
 */

namespace WebAppId\SmartNotify;

use Exception;
use GuzzleHttp\Client;

/**
 * Class SmartNotify
 * @package WebAppId\SmartNotify
 */
class SmartNotify
{

    public const SYSTEM_ERROR = 'SYSTEM_ERROR';

    public const WARNING = 'WARNING';

    public const PUSH = 'PUSH';

    private const ENDPOINT = 'https://push.webappid.com/api/';

    public static function error(string $message,
                                 string $category = self::SYSTEM_ERROR,
                                 string $tokenAccount = '',
                                 string $title = '')
    {

        $report['url'] = request()->url();
        $report['queryString'] = request()->getQueryString();
        $report['method'] = request()->getMethod();
        $report['session'] = request()->getSession()?->all();
        $report['route_param'] = request()->route()?->parameters();
        $report['user'] = request()->user()?->toArray();
        $report['headers'] = request()->headers->all();
        $message = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '

=====================================================================================================================================

        ' . $message;

        self::push($message, $category, $tokenAccount, $title);
    }

    public static function push(string $message,
                                string $category = self::SYSTEM_ERROR,
                                string $tokenAccount = '',
                                string $title = '')
    {

        if (config('sn.token') != null) {
            $url = self::ENDPOINT . 'notify';
            try {
                self::getClient($url)->post($url,
                    [
                        'form_params' => [
                            'token' => config('sn.token'),
                            'token_account' => $tokenAccount,
                            'title' => $title,
                            'messages' => $message,
                            'category' => $category
                        ]
                    ]
                );
            } catch (Exception $exception) {
                dd($exception);
                //nothing to report
            }
        }
    }

    private static function getClient(string $url)
    {
        return new Client(
            ['headers' =>
                [
                    'cookies' => true,
                    'Referer' => $url,
                    'Accept' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'
                ]
            ]);
    }

    public static function register(string $registerCode, string $chatId): ?string
    {
        if (env('NOTIFY_TOKEN') != null) {
            $url = self::ENDPOINT . 'register';

            try {
                $result = self::getClient($url)->post($url,
                    [
                        'form_params' => [
                            'token' => env('NOTIFY_TOKEN'),
                            'register_code' => $registerCode,
                            'chat_id' => $chatId
                        ]
                    ]
                );

                $resultJson = json_decode($result->getBody()->getContents());

                if ($resultJson->code == 201) {
                    return $resultJson->data;
                }
            } catch (Exception $exception) {
                //nothing to report
                return null;
            }
        }
        return null;
    }
}
