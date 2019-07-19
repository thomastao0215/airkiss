
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
  <p id="tips">初始化</P>
  <p id="tips2">开始搜索设备</p>
  <p id="tips3">结果</P>
</body>
<script>
    wx.config({
        beta:true,//开启内测接口调用，注入wx.invoke方法
        debug:false,//关闭调试模式
        appId: 'wx55ec8a96afeecd1c',//AppID
        timestamp: 1559718866,//时间戳
        nonceStr: 'Jev0NNbpsvXC0fV3',//随机串
        signature: '61f1cc92932345a35641a0c964db6d889d316988',//签名
        jsApiList:['openWXDeviceLib','startScanWXDevice','onScanWXDeviceResult','configWXDeviceWiFi']
    });  
    // echo 'start config';
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
				alert("openWXDeviceLib：" + JSON.stringify(res));
				$('#tips').html("初始化成功");
        		});
                        wx.invoke('startScanWXDevice',{'connType':'lan'}, function(res) {
                            console.log('startScanWXDevice',res);
                            alert(JSON.stringify(res));
			    $('#tips2').html("开始搜索设备"+JSON.stringify(res));
			    $('#tips3').html("还未搜索到设备");
                        });

                        wx.on('onScanWXDeviceResult',function(res){
                            $('#tips3').html("扫描到1个设备"+JSON.stringify(res));
			    // localStorage.setItem('devices',res.devices)
			     if (res.devices.length > 0){
			     window.location.href = "https://www.dianxin2019.com/auth/auth?devices="+ JSON.stringify({"devices":res.devices})
				}else {
				$('#tips3').html("请重新配网")
				window.location.reload()
				}
			     //fetch("https://www.dianxin2019.com/auth/auth",{body:JSON.stringify(res)}).then(res =>{
			     //console.log(res):q!
			     //	})
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
	})
</script>
</html>


