
function color_load(colors, color_select_id)
{
	var color_select = document.getElementById(color_select_id);
	color_select.innerHTML = "";
	
	for(var color of colors)
	{
		var _color = document.createElement('option');
		color = color.color;
		_color.value = color.code;
		_color.style.cssText = "color:" + color.code + ";";
		_color.innerHTML = color.name;
		color_select.append(_color);
	}
}

function room_load_table(rooms, room_table_id, row_onclick_callback)
{
	var room_select_table = document.getElementById(room_table_id);
	
	var rowcount = room_select_table.rows.length;
	for(var i = rowcount -1; i>0; i--)
		room_select_table.deleteRow(i);
	
	for(var room of rooms)
	{
		room = room.room;
		var row = room_select_table.insertRow();
		row.setAttribute('data-id', room.id);
		row.setAttribute('data-name', room.room);
		row.setAttribute('data-description', room.description);
		row.setAttribute('data-font', room.font);
		row.setAttribute('data-color', room.color);
		row.setAttribute('data-allow_alias', room.allow_alias);
		row.setAttribute('data-public', room.is_public);
		row.style.cssText = "color: " + room.color + "; font-family: " + room.font + ";";
		row.insertCell().innerHTML=room.id;
		row.insertCell().innerHTML=room.room;
		row.insertCell().innerHTML=room.description;
		row.insertCell().innerHTML=room.font;
		row.insertCell().innerHTML=room.color;
		row.insertCell().innerHTML=room.allow_alias == "1"? "Alias": "Forum Name";
		row.insertCell().innerHTML=room.is_public == "1"? "Public": "Private";
		row.onclick = function(){row_onclick_callback(this);};
	}
}

function room_load_select(rooms, room_select_id, room_default)
{
    var room_select = document.getElementById(room_select_id);
    
    for(var room of rooms)
    {
        var _room = document.createElement('option');
        room = room.room;
        _room.value = room.id;
        _room.innerHTML = room.room + ": " + room.description;
        _room.title = room.description;
        _room.setAttribute('data-font', room.font);
        _room.setAttribute('data-color', room.color);
        _room.setAttribute('data-allow_alias', room.allow_alias);
        room_select.append(_room);
    }
    room_select.value = room_default;
}

function user_load_table(users, user_table_id)
{
	var user_table = document.getElementById(user_table_id);
	
	var rowcount = user_table.rows.length;
	for(var i = rowcount -1; i>0; i--)
		user_table.deleteRow(i);
    
    for(var user of users)
    {
        user = user.user;
        var row = user_table.insertRow();
        row.setAttribute('data-id', user.id);
        row.setAttribute('data-forum_id', user.forum_id);
        row.setAttribute('data-forum_name', user.forum_name);
        row.setAttribute('data-display_name', user.display_name);
        row.setAttribute('data-login', user.login);
        row.setAttribute('data-last_post', user.last_post);
        row.setAttribute('data-logout', user.logout);
        
        var member = window.location.protocol + "//" + window.location.host + "/index.php?members/" + user.forum_id;
        
        row.insertCell().innerHTML="<a href=\"" + member + "\">" + user.forum_name + "</a>";
        row.insertCell().innerHTML=user.display_name;
        row.insertCell().innerHTML=user.login;
        row.insertCell().innerHTML=user.last_post;
        row.insertCell().innerHTML=user.logout;
        
        //row.onclick = function(){row_onclick_callback(this)};
    }
}