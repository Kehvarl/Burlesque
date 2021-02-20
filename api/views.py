from django.contrib.auth import get_user_model
from rest_framework import generics, permissions

from .models import Chat, Post
from .permissions import IsAuthorOrReadOnly
from .serializers import ChatSerializer, PostSerializer, UserSerializer

class ChatList(generics.ListAPIView):
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer

class ChatPosts(generics.RetrieveUpdateDestroyAPIView):
    permission_classes = (IsAuthorOrReadOnly, )
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer

class UserList(generics.ListCreateAPIView):
    queryset = get_user_model().objects.all()
    serializer_class = UserSerializer

class UserDetail)generics.RetrieveUpdateDestroyAPIView):
    queryset = get_user_model().objects.all()
    serializer_class = UserSerializer
