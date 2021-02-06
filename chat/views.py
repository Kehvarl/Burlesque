from django.shortcuts import render
from django.views.generic import ListView

from .models import Chat

# Create your views here.

class ChatListView(ListView):
    model = Book
    template_name = 'book_list.html'
