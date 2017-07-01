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
     * @var string $baiduXML 百度XML结构
     */
    private $baiduXML;
    /**
     * @var string $baiduRPC 百度XML地址
     */
    private $baiduRPC = 'http://ping.baidu.com/ping/RPC2';

    public function __construct($title, $arturl, $hosturl, $rssurl)
    {
        if (empty($title) || empty($arturl)) {
            return false;
        }
        $this->title = $title;
        $this->hosturl = $hosturl;
        $this->rssurl = $rssurl;
        $this->arturl = $arturl;
        $this->baiduXML = '<?xml version=\"1.0\" encoding=\"UTF-8\"?>';
        $this->baiduXML .= '<methodCall>';
        $this->baiduXML .= '  <methodName>weblogUpdates.extendedPing</methodName>';
        $this->baiduXML .= '      <params>';
        $this->baiduXML .= '      <param><value><string>'.$this->hosturl.'</string></value></param>';
        $this->baiduXML .= '      <param><value><string>'.$this->title.'</string></value></param>';
        $this->baiduXML .= '      <param><value><string>'.$this->arturl.'</string></value></param>';
        $this->baiduXML .= '      <param><value><string>'.$this->rssurl.'</string></value></param>';
        $this->baiduXML .= '  </params>';
        $this->baiduXML .= '</methodCall>';
    }

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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->baiduXML);
        $res = curl_exec($ch);
        curl_close($ch);

        return strpos($res, '<int>0</int>') >=0;
    }
}