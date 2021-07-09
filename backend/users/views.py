from django.urls import reverse_lazy
from django.views import generic

from .forms import UserCreationForm


class SignUpPageView(generic.CreateView):
    form_class = UserCreationForm
    success_url = reverse_lazy('users:login')
    template_name = 'users/signup.html'
