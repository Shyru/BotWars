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
        if (message.type=="log")
        {
            handleLogMessage(message);
        }
        else if (message.type=="initField")
        {

            document.getElementById("playfield").style.width=message.width*message.fieldSize+"px";
            document.getElementById("playfield").style.height=message.height*message.fieldSize+"px";
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
    document.getElementById(targetMapping[_logMessage.logTarget]).innerHTML+=_logMessage.logMessage+"\n";

}