<?php
require_once "jssdk.php";
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
$jssdk = new JSSDK("wx55ec8a96afeecd1c", "574613073135a58d4cde2620e938625d");//>这里改成自己的
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
</head>
<body>

</body>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
    beta:true,//开启内测接口调用，注入wx.invoke方法
    debug:true,//关闭调试模式
    appId: '<?php echo $signPackage["appId"];?>',//AppID
    timestamp: <?php echo $signPackage["timestamp"];?>,//时间戳
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',//随机串
    signature: '<?php echo $signPackage["signature"];?>',//签名
    jsApiList:['openWXDeviceLib','startScanWXDevice','onScanWXDeviceResult','configWXDeviceWiFi']
  });

  $(document).ready(function(){  
	    wx.ready(function () {
        // 在这里调用 API
        wx.checkJsApi({
            jsApiList: ['configWXDeviceWiFi'],
            success: function(res) {
                wx.invoke('configWXDeviceWiFi', {}, function(res){
                    var err_msg = res.err_msg;
                    if(err_msg == 'configWXDeviceWiFi:ok') {
                        //配置成功
			alert(err_msg)
                        wx.invoke('openWXDeviceLib',{'connType':'lan'},function(res){
                            alert(res.err_msg);
                        });
                        wx.invoke('startScanWXDevice',{'connType':'lan'}, function(res) {
                            console.log('startScanWXDevice',res);
                            alert(JSON.stringify(res));
                        });
                        wx.on('onScanWXDeviceResult',function(res){
                            alert("扫描到1个设备"+JSON.stringify(res));
                            //自己解析一下res，里面会有deviceid,扫描设备的目的就是为了得到这个
                            //然后就可以开始绑定了
                        });
                    } else {
                        //配置失败
                        alert(err_msg);
                    }
                });
            }
        });
    });
	});
</script>
</html>
