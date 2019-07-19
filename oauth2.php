<?php
  /*网页授板获取用户openid,首先获取code*/
  //echo $code = $_GET['code'];
  /*通过刚刚拿到的code来拿到网页授权的access_token,替换appid与secret与code='.$code.'*/
  $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx55ec8a96afeecd1c&secret=574613073135a58d4cde2620e938625d&code=".$code."&grant_type=authorization_code";
  /*获取token的函数*/
  /*gettoken($url);*/
  /*通过json处理一下返回的数据，把下面的数据输出测试一下，给个变量，最后输出时把这个$openArr的数组赋值给一个属性openid*/
  $openArr=json_decode(gettoken($url),true);
  //   echo $openArr['openid'];
  /*可以使用print_r输出下这个数组*/
  //print_r($openArr);

  function gettoken($url){
    /*如果用curl请求网页，多方网页使用了gzip压缩，那么获取的内容将有可能为乱码的解决办法*/
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
    curl_setopt($ch, CURLOPT_ENCODING ,'gzip'); //加入gzip解析
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
?> 

<html>
   <head>
        <script>
                var url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx55ec8a96afeecd1c&secret=574613073135a58d4cde2620e938625d&code=<?php echo $code= $_GET['code'];?>&grant_type=authorization_code"
               
                
        </script>
   </head>
   <body></body>
</html>

