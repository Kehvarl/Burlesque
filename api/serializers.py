from django.contrib.auth import get_user_model
from rest_framework import serializers

from .models import Chat, Post


class PostSerializer(serializers.ModelSerializer):
    room_id = serializers.PrimaryKeyRelatedField(queryset=Chat.objects.all(), source='room.id')

    class Meta:
        model = Post
        fields = ('room_id', 'name', 'content', 'color', 'created_at')

    def create(self, validated_data):
        subject = Post.objects.create(room=validated_data['chat']['id'],
                                        name=validated_data['name'],
                                        content=validated_data['content'],
                                        color=validated_data['color'])

class ChatSerializer(serializers.ModelSerializer):
    posts = PostSerializer(many=True, read_only=True)
    class Meta:
        model = Chat
        fields = ('owner', 'name', 'description', 'created_at', 'updated_at', 'posts')

class UserSerializer(serializers.ModelSerializer):
    model = get_user_model()
    fields = ('id', 'username')
