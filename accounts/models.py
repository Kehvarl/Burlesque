from django.contrib.auth.models imprt AbstractUser
from django.db import models


class BurlesqueUser(AbstractUser):
    pass

    def __str__(self):
        resturn self.username

