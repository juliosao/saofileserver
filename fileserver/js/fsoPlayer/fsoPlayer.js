function fsoPlayer(tag)
{
	this.playIdx=0;
	this.playlist=[];
	this.tag=tag;
	this.path='';

}

//Method definitions
fsoPlayer.prototype={

	constructor:fsoPlayer,

	//Gets directory data
	load:function(path,mode)
	{
		this.path=path;
		this.mode=mode;

		var data = new FormData();
		data.append('path', path);
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
				me.loadCallBack(JSON.parse(this.responseText),me.tag);
			}
		};
		xhttp.open("POST", "../../api/fso/explore.php", true);
		xhttp.send(data);
	},

	loadCallBack:function(data,tag)
	{
		//  Gets where to put stuff
		var container=document.getElementById(this.tag);
		if(container==null)
			alert("No se encontro "+tag);
		
		// Clears old data
		while (container.firstChild) {
			container.removeChild(container.firstChild);
		}

		// Puts UI
		video=this.loadPlayList(data);

		toolbar=this.putToolBar(container);

		if(this.mode!='audio' || this.playlist.length!=1)
		{
			this.putPlayer(container,true);
		}
		else
		{
			this.putPlayer(toolbar,false);			
		}
		
		if(this.playlist.length!=1)
			this.putPlayList(container);
		
		this.play(this.playIdx);
	},

	loadPlayList:function(data)
	{
		var video=false;
		
		// Gets tracklist
		this.playlist=[];

		for(i in data.files)
		{
			if( (data.files[i].extension=='mp3' || data.files[i].extension=='ogg' || data.files[i].extension=='flac') && this.mode!='video')
			{
				this.playlist.push({ link:data.files[i].link, name:data.files[i].name});	
			}
			else if((data.files[i].extension=='mp4' || data.files[i].extension=='ogv' || data.files[i].extension=='webm') && this.mode!='audio')
			{
				// Video only allows to play one file at time
				this.playlist.push({link:data.files[i].link,name:data.files[i].name});
			}
		}
	},

	putToolBar:function(container)
	{
		var toolbar=document.createElement('div');
		toolbar.id=this.tag+'-toolbar';
		toolbar.className='fsoplayer-toolbar';
		var title=document.createElement('h1');
		title.appendChild(document.createTextNode(this.path));
		toolbar.append(title);

		container.appendChild(toolbar);
		return toolbar;
	},

	putPlayList:function(container)
	{
		//Paint dirs and files
		var lst=document.createElement('table');
		lst.classList.add('table-striped')
		lst.classList.add('table');
		lst.classList.add('fso-player-playlist');

		// Table header
		var hdr = document.createElement('thead');
		var td = document.createElement('th');
		td.appendChild(document.createTextNode('Nombre'));
		td.colSpan=2;
		hdr.appendChild(td);
		lst.appendChild(hdr);

		// Table body
		var tbody=document.createElement('tbody');
		for(var i in this.playlist)
		{
			var me=this;
			var tr = document.createElement('tr');
			tr.id=this.tag+'-playitem-'+i;
			tr.setAttribute('data-idx',i);
			tr.addEventListener('click',function(e){
				me.play(parseInt(e.currentTarget.getAttribute('data-idx')));
			});

			// Number
			td = document.createElement('td');
			td.appendChild(document.createTextNode(''+(parseInt(i)+1)));
			tr.appendChild(td);

			// Name
			td = document.createElement('td');
			td.appendChild(document.createTextNode(decodeURIComponent(this.playlist[i].name)));
			tr.appendChild(td);

			tbody.appendChild(tr);
		}
		lst.appendChild(tbody);
		container.appendChild(lst);


		/*
		var list=document.createElement('ol');
		list.id=this.tag+'-playlist';
		list.className='fso-player-playlist';
		me=this;
		for(var i in this.playlist)
		{
			var li = document.createElement('li');
			li.id=this.tag+'-playitem-'+i;
			li.setAttribute('data-idx',i);
			li.addEventListener('click',function(e){
				me.play(parseInt(e.currentTarget.getAttribute('data-idx')));
			});
			li.appendChild(document.createTextNode(decodeURIComponent(this.playlist[i])));
			list.appendChild(li);
		}
		container.appendChild(list);
		*/
	},

	putPlayer:function(container,video)
	{
		if(this.playlist.length>0)
		{			
			// Video and audio have diferent engines
			if(video)
			{
				var player=document.createElement('video');
				player.id=this.tag+'-player';
				player.setAttribute('width','100%');				
				player.setAttribute('controls','true');
				container.appendChild(player);
			}
			else
			{
				var player=document.createElement('audio');
				player.id=this.tag+'-player';
				player.setAttribute('width','100%');
				player.setAttribute('controls','true');				
				container.appendChild(player);
				
			}
			player.className='fso-player-controls';

			// Creates sources
			var src=document.createElement('source');
			src.id=this.tag+'-player-src';
			player.appendChild(src);
			
			// Control for track-end
			var me=this;
			player.addEventListener("ended", function(){
				me.play( (me.playIdx+1) % me.playlist.length );
			}, false);
		}
	},

	play:function(idx)
	{
		var player=document.getElementById(this.tag+'-player');
		var src=document.getElementById(this.tag+'-player-src');
		
		for(i in this.playlist)
		{
			var elem = document.getElementById(this.tag+'-playitem-'+i);
			if(elem!=null)
			{
				if(i==idx)
					elem.classList.add('selected');
				else
					elem.classList.remove('selected');
			}
		}

		src.src='../../api/fsoPlayer/play.php?path='+this.playlist[idx].link;
		player.load();
		this.playIdx=idx;

		setTimeout(function() {
			
			player.play();	
		}, 500);
		
	}
}


//fsoPlayer initialization
fsoPlayer.setup=function()
{

	fsoPlayer.players=[];

	// Adds plugin to the controllers
	var players=document.getElementsByClassName("fso-player");
	for( var i=0; i<players.length; i++ )
	{
		var player=players[i];
		var p=new fsoPlayer(player.id);
		fsoPlayer.players[player.id]=p;
		p.load(player.getAttribute('data-src'),player.getAttribute('data-mode'));
	}
}


//Calls init
window.addEventListener('load',fsoPlayer.setup);
