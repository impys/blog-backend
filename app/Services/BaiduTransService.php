<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BaiduTransService
{
    protected $appId;
    protected $secret;

    protected $salt;
    protected $baseUrl;

    public function __construct(string $appId, string $secret)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->salt = rand(10000, 99999);
        $this->baseUrl = 'https://fanyi-api.baidu.com/api/trans/vip/translate';
    }

    public function trans(string $text, string $from = 'zh', string $to = 'en')
    {
        $query = $this->buildQuery($text, $from, $to);
        return Http::get($this->baseUrl, $query)['trans_result'][0]['dst'];
    }

    protected function buildQuery(string $text, string $from, string $to): array
    {
        return [
            'q' => $text,
            'appid' => $this->appId,
            'salt' => $this->salt,
            'from' => $from,
            'to' => $to,
            'sign' => $this->sign($text)
        ];
    }

    protected function sign($text)
    {
        return md5($this->appId . $text . $this->salt . $this->secret);
    }
}
