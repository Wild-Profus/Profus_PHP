from django.urls import path
from django.utils.translation import gettext_lazy as _
from .views import index

urlpatterns = [
    path(_('home/'), index, name='home'),
    path('', index, name='index')
]
