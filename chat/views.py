from django.contrib.auth import get_user_model
from rest_framework import generics, permissions

from .models import Room, Post, Name
from .permissions import IsAuthorOrReadOnly
from .serializers import RoomSerializer, PostSerializer, UserSerializer, NameSerializer


class ChatList(generics.ListAPIView):
    queryset = Room.objects.all()
    serializer_class = RoomSerializer


class ChatPosts(generics.RetrieveUpdateDestroyAPIView):
    permission_classes = (IsAuthorOrReadOnly, )
    queryset = Room.objects.all()
    serializer_class = RoomSerializer


class NameList(generics.ListCreateAPIView):
    queryset = Name.objects.all()
    serializer_class = NameSerializer


class NameDetail(generics.RetrieveUpdateDestroyAPIView):
    queryset = Name.objects.all()
    serializer_class = NameSerializer
