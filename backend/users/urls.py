from django.urls import path, include

from .views import SignUpPageView

# https://docs.djangoproject.com/en/3.0/topics/http/urls/#url-namespaces
app_name = 'users'

urlpatterns = [
    # list of urls:
    # https://docs.djangoproject.com/en/3.0/topics/auth/default/#module-django.contrib.auth.views
    path('', include('django.contrib.auth.urls')),
    path('signup/', SignUpPageView.as_view(), name='signup')
]
