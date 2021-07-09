from ..models import Ornament
from ..serializers import OrnamentSerializer, OrnamentLocalSerializer
from ._dofus_viewset import DofusViewSet


class OrnamentViewSet(DofusViewSet):
    model_class = Ornament
    default_serializer = OrnamentSerializer
    local_serializer = OrnamentLocalSerializer
