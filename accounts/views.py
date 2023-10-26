from django.urls import reverse_lazy 
from django.views.generic.edit import CreateView

from .forms import BurlesqueUserCreationForm

class SignupView(CreateView):
    form_class = BurlesqueUserCreationForm
    success_url = reverse_lazy('login')
    template_name = 'registration/signup.html'
