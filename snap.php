<?php
//scope=snsapi_userinfo实例
$appid='wx55ec8a96afeecd1c';
$redirect_uri = urlencode ( 'https://www.dianxin2019.com/getUserInfo.php' );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
header("Location:".$url);
?>
