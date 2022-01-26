<?php
/*
百度网址收录状态检测
*/

$sl_url = (isset($_GET['url'])) ? $_GET['url'] : $_POST['url'];
if (empty($sl_url)) {
    showjson(array('code' => 203, 'msg' => '查询网址不能为空'));
}

$data = curl('https://www.baidu.com/s?wd=' . urlencode($sl_url));

if (!isset($data)) {
    showjson(array('code' => 202, 'msg' => '查询失败，请重试！'));
}

if (!strpos($data, '提交网址')) {
    showjson(array('code' => 200, 'data' => $sl_url, 'msg' => '该网址已被收录！'));
} else {
    showjson(array('code' => 201, 'data' => $sl_url, 'msg' => '该网址未被收录！'));
}

function curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0)
{
    $ch = curl_init();
    $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $httpheader[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
    $httpheader[] = "Accept-Encoding: gzip, deflate, sdch, br";
    $httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
    $httpheader[] = 'X-FORWARDED-FOR:' . $ip;
    $httpheader[] = 'CLIENT-IP:' . $ip;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        if ($referer == 1) {
            curl_setopt($ch, CURLOPT_REFERER, 'https://www.baidu.com');
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if ($ua) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1");
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    //$Headers = curl_getinfo($ch);
    curl_close($ch);
    return $ret;
}

function showjson($arr)
{
    header("Content-Type: application/json; charset=utf-8");
    exit(json_encode($arr, 320));
}
