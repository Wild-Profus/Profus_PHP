from ..models import SpellState
from ..serializers import SpellStateSerializer, SpellStateLocalSerializer
from ._dofus_viewset import DofusViewSet


class SpellStateViewSet(DofusViewSet):
    model_class = SpellState
    default_serializer = SpellStateSerializer
    local_serializer = SpellStateLocalSerializer
