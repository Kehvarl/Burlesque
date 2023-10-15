from django.db import models
from django.utils import timezone
from accounts.models import BurlesqueUser
# Create your models here.


# Extending User Model Using a One-To-One Link
class Room(models.Model):
    owner = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE, related_name="rooms")
    title = models.CharField(max_length=256)
    description = models.TextField()

    def __str__(self):
        return self.title


class Name(models.Model):
    user = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE, related_name="names")
    name = models.CharField(max_length=64)
    bio = models.TextField()
    last_active = models.DateTimeField(auto_now=True)

    def __str__(self):
        return self.name


class Post(models.Model):
    owner = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE)
    room = models.ForeignKey(Room, on_delete=models.CASCADE, related_name="posts")
    name = models.ForeignKey(Name, on_delete=models.CASCADE, related_name="names")
    color = models.CharField(max_length=32)
    font = models.CharField(max_length=64)
    raw = models.TextField()
    display = models.TextField()
    is_visible = models.BooleanField(default=True)
    date = models.DateTimeField(auto_now=True)

    def __str__(self):
        return self.name.name
