from ..models import ItemPrice
from ..serializers import ItemPriceSerializer
from ._dofus_viewset import DofusViewSet


class ItemPriceViewSet(DofusViewSet):
    model_class = ItemPrice
    default_serializer = ItemPriceSerializer
    local_serializer = ItemPriceSerializer

    def get_queryset(self):
        if self.action in ['create', 'update', 'partial_update', 'retrieve']:
            queryset = self.model_class.objects.all()
        else:
            # lang = self.request.LANGUAGE_CODE
            queryset = self.model_class.objects.local_only(server=5, category=0)
        return queryset