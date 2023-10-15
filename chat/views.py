from django.shortcuts import render

from .models import Room, Post, Name


def index_view(request):
    return render(request, 'chat/home.html', {
        'rooms': Room.objects.all(),
    })


def room_view(request, room_name):
    chat_room, created = Room.objects.get_or_create(title=room_name)
    return render(request, 'chat/room.html', {
        'room': chat_room,
    })
