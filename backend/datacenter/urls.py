from django.urls import path, include
from .routers import VersionRouter

app_name = 'datacenter'

router = VersionRouter()

urlpatterns = [
    path('dofus2/', include('datacenter.dofus2.urls', namespace='dofus2')),
    path('retro/', include('datacenter.retro.urls', namespace='retro')),
    path('touch/', include('datacenter.touch.urls', namespace='touch')),
    path('', include(router.urls)),
]
