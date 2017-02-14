function init_manage_form()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque_Manage.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	xhr.send(JSON.stringify({
		'action': 'init'
	}));
	
	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			var html = "";
			if(typeof(response.error) != "undefined")
			{
				html = "There was an error accessing the data";
			}
			else
			{
				color_load(response.colors, 'room_color');
				room_load_table(response.rooms, 'room_select_table', room_change_table);
			}
			
			document.getElementById("messages").innerHTML = html;
		}
	};
}

function delete_room()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque_Manage.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	var room_id = document.getElementById("room_id").value;
	var room_name = document.getElementById("room_name").value;
	var room_desc = document.getElementById("room_description").value;
	var room_font = document.getElementById("room_font").value;
	var room_color = document.getElementById("room_color").value;
	var allow_alias = document.getElementById("room_allow_alias").checked;
	var room_public = document.getElementById("room_is_public").checked;
	
	xhr.send(JSON.stringify({
		'action': 'deleteRoom',
		'room'  :
		{
			'id'    : room_id,
			'room'  : room_name,
			'description' : room_desc,
			'color' : room_color,
			'font'  : room_font,
			'allow_alias'   : allow_alias,
			'is_public'     : room_public
		}
	}));
			 
	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			var messages = document.getElementById('messages');
			
			messages.innerHTML += "<br\> Removed Room: (" + response.room.room.id + ") "  + response.room.room.room;
			
			room_load_table(response.rooms, 'room_select_table', room_change_table);
			document.getElementById("room_edit_interface").style.cssText = "display: none;";
			document.getElementById("users_interface").style.cssText = "display: none;";
		}
	};
}

function save_room()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque_Manage.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	var room_id = document.getElementById("room_id").value;
	var room_name = document.getElementById("room_name").value;
	var room_desc = document.getElementById("room_description").value;
	var room_font = document.getElementById("room_font").value;
	var room_color = document.getElementById("room_color").value;
	var allow_alias = document.getElementById("room_allow_alias").checked;
	var room_public = document.getElementById("room_is_public").checked;
	
	xhr.send(JSON.stringify({
		'action': 'saveRoom',
		'room'  :
		{
			'id'    : room_id,
			'room'  : room_name,
			'description' : room_desc,
			'color' : room_color,
			'font'  : room_font,
			'allow_alias'   : allow_alias,
			'is_public'     : room_public
		}
	}));

	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			room_load_table(response.rooms, 'room_select_table', room_change_table);
		}
	};
}

function get_room_users(room_id)
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "Burlesque_Manage.php", true);
	xhr.setRequestHeader('Content-Type','application/json; charset=UTF-8');
	
	xhr.send(JSON.stringify({
		'action': 'getUsers',
		'room'  :
		{
			'id'    : room_id
		}
	}));
			 
	xhr.onloadend = function()
	{
		if(xhr.readyState == 4)
		{
			var response = JSON.parse(xhr.responseText);
			user_load_table(response.users, 'room_users_table');
		}
	};
}

function room_change_table(room)
{
	var allow_alias = room.getAttribute('data-allow_alias');
	var room_public = room.getAttribute('data-public');
	var room_font   = room.getAttribute('data-font');
	var room_color  = room.getAttribute('data-color');
	var room_desc   = room.getAttribute('data-description');
	var room_name   = room.getAttribute('data-name');
	var room_id     = room.getAttribute('data-id');
	
	document.getElementById("room_id").value = room_id;
	document.getElementById("room_name").value = room_name;
	document.getElementById("room_description").value = room_desc;
	document.getElementById("room_font").value = room_font;
	document.getElementById("room_color").value = room_color;
	document.getElementById("room_allow_alias").checked = (allow_alias === "1");
	document.getElementById("room_is_public").checked = (room_public === "1");
	
	document.getElementById('room_name').style.fontFamily=room_font;
	document.getElementById('room_description').style.fontFamily=room_font;
	
	document.getElementById("room_edit_interface").style.cssText = "display: block;";
	color_change();
	get_room_users(room_id);
	document.getElementById("users_interface").style.cssText = "display: block;";
}

function color_change()
{
	var color = document.getElementById('room_color').value;
	document.getElementById('room_name').style.color=color;
	document.getElementById('room_description').style.color=color;
}