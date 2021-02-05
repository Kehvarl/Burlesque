from django.db import models

# Create your models here.
class Chat(models.Model):
    title = models.CharField(max_length=250)
    description = models.CharField(max_length=250)
    roleplay = models.BooleanField(help_text="If Yes, this room is intended for Roleplaying")
    masquerade = models.BooleanField(help_text="If Yes, then no user information will be present in chat, only the Character Name")

    def __str__ (self):
        return self.title
