<?php

namespace Cheukpang;

/**
 * Class BaiduPing
 * @package Cheukpang
 */
class BaiduPing
{
    /**
     * @var string $title 博客名称
     */
    private $title;
    /**
     * @var string $hosturl 博客首页地址
     */
    private $hosturl;
    /**
     * @var string $arturl 新发文章地址
     */
    private $arturl;
    /**
     * @var string $rssurl 博客rss地址
     */
    private $rssurl;
    /**
     * @var string $baiduRPC 百度XML地址
     */
    private $baiduRPC = 'http://ping.baidu.com/ping/RPC2';

    /**
     * @return bool 返回结果为true 表示成功
     */
    public function ping()
    {
        $ch = curl_init();
        $headers = [
            'User-Agent: request',
            'Host: ping.baidu.com',
            'Content-Type: text/xml',
        ];
        curl_setopt($ch, CURLOPT_URL, $this->baiduRPC);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getBaiduXML());
        $res = curl_exec($ch);
        curl_close($ch);
        $xmlstring = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($xmlstring), true);

        return $data['params']['param']['value']['int'] == 0;
    }

    /**
     * @param string $title
     * @return BaiduPing
     */
    public function setTitle(string $title): BaiduPing
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $hosturl
     * @return BaiduPing
     */
    public function setHosturl(string $hosturl): BaiduPing
    {
        $this->hosturl = $hosturl;

        return $this;
    }

    /**
     * @param string $arturl
     * @return BaiduPing
     */
    public function setArturl(string $arturl): BaiduPing
    {
        $this->arturl = $arturl;

        return $this;
    }

    /**
     * @param string $rssurl
     * @return BaiduPing
     */
    public function setRssurl(string $rssurl): BaiduPing
    {
        $this->rssurl = $rssurl;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaiduXML(): string
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodCall>
    <methodName>weblogUpdates.extendedPing</methodName>
        <params>
            <param><value><string>%s</string></value></param>
            <param><value><string>%s</string></value></param>
            <param><value><string>%s</string></value></param>
            <param><value><string>%s</string></value></param>
    </params>
</methodCall>
EOF;

        return sprintf($xml, $this->hosturl, $this->title, $this->arturl, $this->rssurl);
    }
}