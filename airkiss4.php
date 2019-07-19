<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx55ec8a96afeecd1c", "574613073135a58d4cde2620e938625d");//>这里改成自己的
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
  <title></title>
</head>
<body style="background:rgb(224,224,224)">
<div align="center"><img src="https://cloud-minapp-25678.cloud.ifanrusercontent.com/1hdTP43mg6yXaX89.gif" style="width:60vw;margin:10vw" /></div>
<p id="tips" style="text-align:center">开始搜索设备</P>
  <p id="tips2" style="text-align:center"></p>
  <p id="tips3" style="text-align:center"></P>
  <p id="tips4" style="text-align:center"></P>
  <p id="tips5" style="text-align:center"></P>
  <p id="tips6" style="text-align:center"></P>
  <p id="tips7" style="text-align:center"></P>
  <p id="toast" style="text-align:center"></P>
</body>
<link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css" />
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://dl.ifanr.cn/hydrogen/sdk/sdk-web-latest.js"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>

<script>
    
    function getUrlParam(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) return unescape(r[2]); return null;
    }

    var unionid = getUrlParam('unionid')
    

    wx.config({
        beta:true,//开启内测接口调用，注入wx.invoke方法
        debug:false,//关闭调试模式
        appId: '<?php echo $signPackage["appId"];?>',//AppID
        timestamp: <?php echo $signPackage["timestamp"];?>,//时间戳
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',//随机串
        signature: '<?php echo $signPackage["signature"];?>',//签名
        jsApiList:['openWXDeviceLib','startScanWXDevice','onScanWXDeviceResult','configWXDeviceWiFi']
    });
    wx.ready(function () {
    // 在这里调用 API
        wx.checkJsApi({
                    jsApiList: ['openWXDeviceLib','startScanWXDevice','onScanWXDeviceResult','configWXDeviceWiFi'],
                    success: function(res) {
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
                                })
                                wx.on('onScanWXDeviceResult',function(res){
                                    if (res.devices.length > 0){
                                        $('#tips3').html("扫描到"+res.devices.length+"个设备"+JSON.stringify(res));
                                        var deviceId = res.devices[0].deviceId
                                        let cacheKey = '93e0c6c9010f1b2a4d21'
                                        let BaaS = window.BaaS
                                        BaaS.init(cacheKey)
                                        BaaS.auth.login({username: 'admin', password: 'admin'}).then(user => {
                                            $('#tips4').html(JSON.stringify(user));
                                            let MyUser = new BaaS.User()
                                            let query = new BaaS.Query()
                                            query.compare('unionid','=',unionid)
                                            MyUser.setQuery(query).find().then(res => {
                                                let result = res.data.objects
                                                let object = res.data.objects[0]
                                                $('#tips6').html(object.id)
                                                
                                                let userx = new BaaS.User().getWithoutData(object.id)
                                                let device_query = new BaaS.Query()
                                                device_query.compare('mac','=',deviceId)
                                                let Devices = new BaaS.TableObject('devices')
						
                                                let Device_Auth = new BaaS.TableObject('device_auth')                                                
                                                Devices.setQuery(device_query).find().then(res => {
                                                    if (res.data.objects.length > 0){
                                                        $('#tips5').html(deviceId+'设备已存在')
                                                    }else {
                                                        let record = Devices.create()

                                                var device = {
                                                    deviceName:'测试插座'+new Date(),
                                                    mac:deviceId,
                                                    owner:userx,
                                                    user:userx
                                                }
                                                $('#tips5').html(device)
                                                record.set(device)
                                                .save()
                                                .then(res => {
                                                    
						    let DEVICEID = res.data.id
						    
                                                    let device_auth = Device_Auth.create()
                                                        var auth = {
                                                            mac:deviceId,
                                                            owner:userx,
                                                            user:userx,
							    device:DEVICEID
                                                        }
							  
                                                        device_auth
                                                        .set(auth)
                                                        .save()
                                                        .then(resx => {
                                                            $('#tips7').html('设备绑定成功，请关闭页面')
                                                        })
                                                })
                                                .catch(err=>{
                                                    $('#tips6').html(res)
                                                })
                                                    }
                                                })




                                            })
                                            
                                            

                                        })
                                    }
                                })
                            } else {
                                //配置失败
                                $('#tips5').html(err_msg)
                            }
                        });
                        
                    }
                });
        });
    wx.error(function(res){
        $('#tips3').html("配置出错:"+res);
    });
  </script>
  </html>
