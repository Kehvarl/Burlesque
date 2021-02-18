from rest_framework import generics, permissions

from .models import Chat, Post
from .serializers import ChatSerializer, PostSerializer

class ChatList(generics.ListAPIView):
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer

class ChatPosts(generics.RetrieveUpdateDestroyAPIView):
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer
