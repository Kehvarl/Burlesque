from django.contrib.auth import get_user_model
from rest_framework import serializers
from .models import Room, Name, Post


class PostSerializer(serializers.ModelSerializer):
    room_id = serializers.PrimaryKeyRelatedField(queryset=Room.objects.all(), source='room.id')

    class Meta:
        model = Post
        fields = ('room_id', 'name', 'content', 'color', 'created_at')

    def create(self, validated_data):
        subject = Post.objects.create(room=validated_data['room']['id'],
                                      name=validated_data['name'],
                                      content=validated_data['content'],
                                      color=validated_data['color'])


class RoomSerializer(serializers.ModelSerializer):
    posts = PostSerializer(many=True, read_only=True)

    class Meta:
        model = Room
        fields = ('owner', 'title', 'description', 'posts')


class NameSerializer(serializers.ModelSerializer):
    class Meta:
        model = Name
        fields = ('user', 'name', 'bio', 'last_active')


class UserSerializer(serializers.ModelSerializer):
    model = get_user_model()
    fields = ('id', 'username')
