from rest_framework import generics, permissions

from .models import Chat, Post
from .permissions import IsAuthorOrReadOnly
from .serializers import ChatSerializer, PostSerializer

class ChatList(generics.ListAPIView):
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer

class ChatPosts(generics.RetrieveUpdateDestroyAPIView):
    permission_classes = (IsAuthorOrReadOnly, )
    queryset = Chat.objects.all()
    serializer_class = ChatSerializer
