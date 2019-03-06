<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font: 13px Helvetica, Arial; }
        form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
        form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
        form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
        #messages { list-style-type: none; margin: 0; padding: 0; }
        #messages li { padding: 5px 10px; }
        #messages li:nth-child(odd) { background: #eee; }
    </style>
    <title>Ws</title>
</head>
<body>
    <ul id="messages"></ul>
    <form action="">
        <input id="m" autocomplete="off" /><button>Send</button>
    </form>
    <script>
        var ws= new WebSocket("ws://192.168.74.130:8081",'echo-protocol');
        console.log(ws);
        ws.onopen = function(){
            console.log('send ...');
            //Web Socket 已经连接上，使用send()方法发送数据
            ws.send("发送数据");
        }
        ws.onmessage =function(evt){
            var received_msg =evt.data;
            console.log('Receiv...');
            console.log(evt);

        }
    </script>
</body>
</html>