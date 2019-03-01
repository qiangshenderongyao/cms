<h2>JS SDK</h2>
<button id="btn">图片吧</button>
<button id="btn1">扫一扫</button>
<button id="btn2">地理位置</button>
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="http://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>

<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$jsconfig['appid']}}", // 必填，公众号的唯一标识
        timestamp: {{$jsconfig['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: "{{$jsconfig['noncestr']}}", // 必填，生成签名的随机串
        signature: "{{$jsconfig['sign']}}",// 必填，签名
        jsApiList: ['chooseImage','uploadImage','getLocalImgData','startRecord','scanQRCode','openLocation'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function() {
        $("#btn").click(function () {
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                }
            });
        });
        $("#btn1").click(function () {
            wx.scanQRCode({
                needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                }
            });
        });
        $("#btn2").click(function () {
            wx.openLocation({
                latitude: 0, // 纬度，浮点数，范围为90 ~ -90
                longitude: 0, // 经度，浮点数，范围为180 ~ -180。
                name: '', // 位置名
                address: '', // 地址详情说明
                scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
                infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
            });
        });
    })
</script>