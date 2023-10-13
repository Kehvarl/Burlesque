from django.contrib.auth.forms import UserCreationForm, UserChangeForm

from .models import BurlesqueUser


class BurlesqueUserCreationForm(UserCreationForm):

    class Meta:
        model = BurlesqueUser
        fields = ('email',)


class BurlesqueUserChangeForm(UserChangeForm):

    class Meta:
        model = BurlesqueUser
        fields = ('email',)
