var prefixes = ['Me', 'Act', 'Do', 'Fate', 'Roll'];

function init_login_form()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	xhr.send(JSON.stringify({
		'action': 'init'
	}));

	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			var rooms = document.getElementById('login_rooms');
			var rooms_label = document.getElementById('login_label');
			var colors = document.getElementById('login_colors');
			var message = document.getElementById('login_message');
			message.value = "";
			
			color_load(response.colors, 'login_colors');
			color_change(colors);
			
			room_load_select(response.rooms, 'login_rooms', default_room_id);
			room_change();
			
			if(!allow_room_select)
			{
				rooms.style.cssText="display:none;";
				rooms_label.style.cssText="display:none;";
			}
		}
	};				
}

function login()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	var room = document.getElementById('login_rooms').value;
	var color = document.getElementById('login_colors').value;
	var display_name = document.getElementById('login_name').value;
	var login_message = document.getElementById('login_message').value;
	
	xhr.send(JSON.stringify({
		'action': 'login',
		'load'  : room,
		'data' : {
			'room_id'  : room,
			'color' : color,
			'font'  : default_font,
			'display_name': display_name,
			'message': login_message
		}
	}));

	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			color_load(response.colors, 'post_colors');
			
			document.getElementById("post_form").style.cssText  = "display:block;";
			document.getElementById("login_form").style.cssText  = "display:none;";
			
			load_post_form(response.login);
			load_posts(response.posts);
		
			reload_timer = setTimeout(load, reload_interval);
		}
	};
}

function post_message()
{
	clearTimeout(reload_timer);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	var post_color		= document.getElementById('post_colors').value;
	var post_name 		= document.getElementById('post_name').value;
	var post_room 		= document.getElementById('post_room').value;
	var post_id 		= document.getElementById('post_id').value;
	var post_message	= document.getElementById('post_message').value;
	
	if(post_message == "")
	{
		reload_timer = setTimeout(load, reload_interval);
		return;
	}
	else
	{
		document.getElementById('post_message').value = "";
	}
	
	xhr.send(JSON.stringify({
		'action': 'post',
		'load'  : post_room,
		'data' : {
			'room_id'  		: post_room,
			'color' 		: post_color,
			'font'  		: default_font,
			'user_id'		: post_id,
			'display_name'	: post_name,
			'message'		: post_message
		}
	}));

	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			load_post_form(response.user);
			load_posts(response.posts);
			reload_timer = setTimeout(load, reload_interval);
		}
	};
}

function load()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');

	var post_room 		= document.getElementById('post_room').value;
	
	xhr.send(JSON.stringify({
		'action': 'load',
		'load'  : post_room
	}));

	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			load_posts(response.posts);
			reload_timer = setTimeout(load, reload_interval);
		}
	};
}

function room_change()
{
	var color = document.getElementById('login_colors');
	var name = document.getElementById('login_name');
	var rooms = document.getElementById('login_rooms');
	var option = rooms.options[rooms.selectedIndex];
	var allow_alias = option.getAttribute('data-allow_alias');
	var room_font = option.getAttribute('data-font');
	var room_color = option.getAttribute('data-color');
	if(allow_alias === "1")
	{
		name.style.cssText = "";
	}
	else
	{
		name.value = "";
		name.style.cssText = "display: none;";
	}
	default_color = room_color;
	default_font = room_font;
	color.value = default_color;
	color_change(color);
}

function color_change(color_select)
{
	var color = color_select.value;
	document.getElementById('login_name'   ).style.color=color;
	document.getElementById('login_message').style.color=color;
	document.getElementById('post_message' ).style.color=color;
}

function load_posts(posts)
{
	var messages = document.getElementById('messages');
	messages.innerHTML = "";
	for(post of posts)
	{
		post = post.post;
		
		if(Number(post.id) > Number(last_post_id))
		{
			last_post_id = Number(post.id);
			if(document.hidden)
			{
				PageTitleNotification.On("New Chat Message!");
			}
			
			window.onfocus = function(){
				if(!document.hidden) {PageTitleNotification.Off();}
			};
		}
		
		var line = document.createElement("div");
		line.style.color = post.color;
		line.style.fontFamily = post.font;
		var html  = "";
		if(post.prefix !== "")
		{
			html += "<span class=\"prefix\" style=\"color:" + post.prefix_color;
			html += ";\" >{" + post.prefix + "}</span>";
		}
		html += "<span class=\"username\">" + post.sender + "</span>";
		if(post.sender != "" && prefixes.indexOf(post.prefix) == -1)
			html += ": ";
		html += " <span class=\"message\">" + post.message + "</span>";
		html += "<span class=\"timestamp\"> [" + post.timestamp + "] </span>";
		line.innerHTML = html;
		messages.appendChild(line);
	}
}

function load_post_form(data)
{
	var post_colors_select	= document.getElementById('post_colors');
	var post_name 			= document.getElementById('post_name');
	var post_room 			= document.getElementById('post_room');
	var post_id 			= document.getElementById('post_id');
	var post_message		= document.getElementById('post_message');
	var username 			= document.getElementById('username');	
	post_colors_select.value 	= data.settings.color;
	post_name.value				= data.user.display_name;
	post_room.value				= data.user.room_id;
	post_id.value				= data.user.id;
	post_message.value 			= "";
	username.innerHTML 			= data.user.display_name;
	post_message.focus();
}

 function onkeyup_check(e){
	var enterKey = 13;
	var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
	if (charCode== enterKey)
	{
		post_message();
	}
}

var PageTitleNotification = {
    Vars:{
        OriginalTitle: document.title ? document.title : parent.document.title,
        Interval: null
    },    
    On: function(notification, intervalSpeed){
        clearInterval(this.Vars.Interval);
		document.title = this.Vars.OriginalTitle;   
        parent.document.title = document.title;
        var _this = this;
        _this.Vars.Interval = setInterval(function(){
             document.title = (_this.Vars.OriginalTitle == document.title)
                                 ? notification
                                 : _this.Vars.OriginalTitle;
			 parent.document.title = document.title;
        }, (intervalSpeed) ? intervalSpeed : 1000);
    },
    Off: function(){
        clearInterval(this.Vars.Interval);
		document.title = this.Vars.OriginalTitle;   
        parent.document.title = document.title;
    }
}