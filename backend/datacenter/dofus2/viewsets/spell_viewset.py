from ..models import Spell
from ..serializers import SpellSerializer, SpellLocalSerializer
from ._dofus_viewset import DofusViewSet


class SpellViewSet(DofusViewSet):
    model_class = Spell
    default_serializer = SpellSerializer
    local_serializer = SpellLocalSerializer
