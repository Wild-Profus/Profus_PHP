from ..models import Emote
from ..serializers import EmoteSerializer, EmoteLocalSerializer
from ._dofus_viewset import DofusViewSet


class EmoteViewSet(DofusViewSet):
    model_class = Emote
    default_serializer = EmoteSerializer
    local_serializer = EmoteLocalSerializer
