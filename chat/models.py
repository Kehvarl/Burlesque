from django.conf import settings
from django.db import models
from django.utils import timezone

# Create your models here.

class Post(models.Model):
    author = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
    name = models.TextField()
    text = models.TextField()
    bgcolor = models.TextField()
    color = models.TextField()
    created_date = models.DateTimeField(default=timezone.now)

    def __str__(self):

        return self.text
