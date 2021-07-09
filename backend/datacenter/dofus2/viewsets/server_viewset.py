from ..models import Server
from ..serializers import ServerSerializer
from ._dofus_viewset import DofusViewSet


class ServerViewSet(DofusViewSet):
    model_class = Server
    default_serializer = ServerSerializer
    local_serializer = ServerSerializer
