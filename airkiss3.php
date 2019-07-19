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
<p id="tips">初始化</P>
  <p id="tips2">开始搜索设备</p>
  <p id="tips3">结果</P>
</body>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://dl.ifanr.cn/hydrogen/sdk/sdk-web-latest.js"></script>
<script>
    
	function getUrlParam(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!=null) return unescape(r[2]); return null;
}
    var unionid = getUrlParam('unionid')
    alert(unionid)
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
        console.log("开始")
        wx.checkJsApi({
            jsApiList: ['openWXDeviceLib','startScanWXDevice','onScanWXDeviceResult','configWXDeviceWiFi'],
            success: function(res) {
                $('#tips').html(res);
                wx.invoke('configWXDeviceWiFi', {}, function(res){
                    var err_msg = res.err_msg;
                    if(err_msg == 'configWXDeviceWiFi:ok') {
                        //配置成功
                        wx.invoke('openWXDeviceLib', {'connType': 'lan', 'brandUserName': 'gh_8db49b0fd4a5'}, function (res) {
                                
                                $('#tips').html("初始化成功,unionid:"+unionid);
                        });
                        wx.invoke('startScanWXDevice',{'connType':'lan'}, function(res) {
                            console.log('startScanWXDevice',res);
                            
			    $('#tips').html("初始化成功,unionid:"+unionid);
                            $('#tips2').html("开始搜索设备"+JSON.stringify(res));
                            $('#tips3').html("还未搜索到设备");
			     var data3 = 0
		 	      (function count3(){
				data3++
       				$('#tips3').html("还未搜索设备…… ："+data3+"s");
				if(data3 === 300000){
					window.location.reload()
				}
       				setTimeout(count3,1000);
				})();
                        });

                        wx.on('onScanWXDeviceResult',function(res){
                            $('#tips3').html("扫描到1个设备"+JSON.stringify(res));
                            // localStorage.setItem('devices',res.devices)
                             if (res.devices.length > 0){
                             let cacheKey = '93e0c6c9010f1b2a4d21'
let BaaS = window.BaaS
BaaS.init(cacheKey)

BaaS.auth.login({username: 'admin', password: 'admin'}).then(user => {
    
    let MyUser = new BaaS.User()
    // 查询 nickname 中包含 like 的用户
    let query = new BaaS.Query()
    query.compare('unionid','=',unionid)
    MyUser.setQuery(query).find().then(res => {
        // success
        let result = res.data.objects
        console.log(result)
        let Devices = new BaaS.TableObject('devices')

        let object = res.data.objects[0]
        console.log(object.id)
        let user = new BaaS.User().getWithoutData(object.id)
        let record = Devices.create()
        record.set({
            deviceName:'测试插座'+new Date(),
            mac:res.devices[0].deviceId,
            owner:user,
            user:user
            }).save().then(res => {
                $('#tips3').html(res)
            })
        
    }, err => {
     // err
	$('#tips3').html(err)
    })
})
                                }else {
                                $('#tips3').html("请重新配网")
                                window.location.reload()
				}
                             //fetch("https://www.dianxin2019.com/auth/auth",{body:JSON.stringify(res)}).then(res =>{
                             //console.log(res):q!
                             // })
                            //自己解析一下res，里面:会有deviceid,扫描设备的目的就是为了得到这个
                            //然后就可以开始绑定了
                        });
                        
                    } else {
                        //配置失败
                        $('#tips3').html(err_msg);
                    }
                });

            }
        });
    });
    wx.error(function(res){
        $('#tips3').html("配置出错:"+res);
    });
        });
</script>
</html>
