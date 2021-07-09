from rest_framework import serializers

from datacenter.dofus2.models import ItemPrice


class ItemPriceSerializer(serializers.ModelSerializer):

    class Meta:
        model = ItemPrice
        fields = [
            "item_id",
            "price",
            "creation_date",
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        data.move_to_end('asset_id')
        return data
