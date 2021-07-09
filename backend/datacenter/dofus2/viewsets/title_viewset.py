from ..models import Title
from ..serializers import TitleSerializer, TitleLocalSerializer
from ._dofus_viewset import DofusViewSet


class TitleViewSet(DofusViewSet):
    model_class = Title
    default_serializer = TitleSerializer
    local_serializer = TitleLocalSerializer
