function playThisAcc(accId, accName){
	$("#jquery_jplayer_1").jPlayer(
			"setMedia", 
			{
				mp3: "/accompaniment/preview/id/" + accId
			}
			);
	$("#jp-title").html("<ul><li>试听《" + accName + "》伴奏的前1/3</li></ul>");
    $("#jquery_jplayer_1").jPlayer("play");
	
}