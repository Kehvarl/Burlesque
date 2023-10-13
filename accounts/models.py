from django.contrib.auth.models import AbstractUser
from django.db import models
from django.utils.translation import gettext as _

from .managers import BurlesqueUserManager


class BurlesqueUser(AbstractUser):
    email = models.EmailField(_('email address'), unique=True)

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = ('username',)

    objects = BurlesqueUserManager()

    def __str__(self):
        return self.email

