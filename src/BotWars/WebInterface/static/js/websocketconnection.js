/**
 * Created by Daniel on 31.07.14.
 */

function connect(_port)
{
    var uri="ws://"+document.location.hostname+":"+_port;
    console.log("Connecting to",uri);
    websocket = new WebSocket(uri);

    websocket.onopen = function(evt) { onOpen(evt) };
    websocket.onclose = function(evt) { onClose(evt) };
    websocket.onmessage = function(evt) { onMessage(evt) };
    websocket.onerror = function(evt) { onError(evt) };
}

function onOpen(evt)
{
    console.log("onOpen",arguments);
}

function onClose()
{
    document.getElementById('globalLog').innerHTML+='<span style="color:red;">Lost connection to server!</span>';
}

function onMessage(evt)
{
    var message=JSON.parse(evt.data);
    console.log("Got message!",message);
    if (message.type)
    { //we got a valid message
        if (message.type=="AppendToLogMessage")
        {
            handleLogMessage(message);
        }
        else if (message.type=="SetupPlayFieldMessage")
        {
            //first adjust the size of our playfield
            var tileSize=message.tileSize;
            document.getElementById("playfield").style.width=message.width*tileSize+"px";
            document.getElementById("playfield").style.height=message.height*tileSize+"px";
            var imageHtml="";
            //now add images for every tile:
            for (var x=0; x<message.tiles.length; x++)
            {
                for (var y=0; y<message.tiles[x].length; y++)
                {
                    imageHtml+='<img src="'+message.tiles[x][y].imageUrl+'" width="'+tileSize+'px" height="'+tileSize+'px" alt=""/>'
                }
            }
            document.getElementById("playfield").innerHTML=imageHtml;
        }
    }

}

function onError(evt)
{
    console.log("onError",arguments);
}

function handleLogMessage(_logMessage)
{
    var targetMapping={
        "global":"globalLog"
    };
    document.getElementById(targetMapping[_logMessage.logName]).innerHTML+=_logMessage.message+"\n";

}