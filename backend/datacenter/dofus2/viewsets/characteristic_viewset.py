from ..models import Characteristic
from ..serializers import CharacteristicSerializer, CharacteristicLocalSerializer
from ._dofus_viewset import DofusViewSet


class CharacteristicViewSet(DofusViewSet):
    model_class = Characteristic
    default_serializer = CharacteristicSerializer
    local_serializer = CharacteristicLocalSerializer
