from django.db import models
from django.utils import timezone
from accounts.models import BurlesqueUser
# Create your models here.


# Extending User Model Using a One-To-One Link
class Name(models.Model):
    user = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE, related_name="names")
    name = models.CharField(max_length=64)
    bio = models.TextField()
    last_active = models.DateTimeField(auto_now=True)

    def __str__(self):
        return self.name


class Room(models.Model):
    owner = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE, related_name="rooms")
    title = models.CharField(max_length=256)
    description = models.TextField()
    online = models.ManyToManyField(to=Name, blank=True)

    def get_online_count(self):
        return self.online.count()

    def join(self, name):
        self.online.add(name)
        self.save()

    def leave(self, name):
        self.online.remove(name)
        self.save()

    def __str__(self):
        return f'{self.title} ({self.get_online_count()})'


class Post(models.Model):
    owner = models.ForeignKey(BurlesqueUser, on_delete=models.CASCADE)
    room = models.ForeignKey(Room, on_delete=models.CASCADE, related_name="posts")
    name = models.ForeignKey(Name, on_delete=models.CASCADE, related_name="names")
    color = models.CharField(max_length=32)
    font = models.CharField(max_length=64)
    raw = models.TextField()
    display = models.TextField()
    is_visible = models.BooleanField(default=True)
    timestamp = models.DateTimeField(auto_now=True)

    def __str__(self):
        return f'{self.name.name}: {self.raw} [{self.timestamp}]'
