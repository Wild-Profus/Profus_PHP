from django.urls import path, include
from rest_framework import routers
# from . import viewsets

app_name = 'datacenter.touch'

router = routers.DefaultRouter()

urlpatterns = [
    path('', include(router.urls)),
]
