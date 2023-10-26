from django.contrib import admin
from django.contrib.auth.admin import UserAdmin

from .forms import BurlesqueUserCreationForm, BurlesqueUserChangeForm
from .models import BurlesqueUser


class BurlesqueUserAdmin(UserAdmin):
    add_form = BurlesqueUserCreationForm
    form = BurlesqueUserChangeForm
    model = BurlesqueUser
    list_display = ['email', 'username']

admin.site.register(BurlesqueUser, BurlesqueUserAdmin)

