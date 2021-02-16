from django.db import models
from django.contrib.auth.models import User

# Create your models here.
class Chat(models.Model):
    owner = models.ForeignKey(User, on_delete=models.CASCADE)
    name = models.CharField(max_length=64)
    description = models.TextField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_ad = models.DateTimeField(auto_now=True)

    def __str__(self):
        return self.name

class Post(models.Model):
    owner = models.ForeignKey(User, on_delete=models.CASCADE)
    room = models.ForeignKey(Chat, on_delete=models.CASCADE)
    name = models.CharField(max_length=64)
    content = models.TextField()
    color = models.CharField(max_length=7)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f"From: {self.name} in {self.room} at: {self.created_at}"
