//Code to report when a video is viewed

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var players = [];

function onYouTubePlayerAPIReady() { 
    var frames = document.getElementsByTagName("iframe");
    for (i = 0; i < frames.length; i++) {
    players[frames[i].id] = new YT.Player(frames[i].id);
    }
    setInterval(function(){
    for (var key in players) {
        if(players[key]!=null && players[key].getPlayerState && players[key].getPlayerState()==1){
            viewVideo(key.replace( /^\D+/g, ''));
            players[key]=null;
        }
    }
    }, 200);
}

//Report viewed video to server
function viewVideo(id){
    var ajax=new XMLHttpRequest();
    ajax.open("GET","addview.php?id="+id,true);
    ajax.send();
}