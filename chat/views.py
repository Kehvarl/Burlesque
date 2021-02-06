from django.shortcuts import render
from django.views.generic import ListView

from .models import Chat

# Create your views here.

class ChatListView(ListView):
    model = Chat
    template_name = 'chat_list.html'
