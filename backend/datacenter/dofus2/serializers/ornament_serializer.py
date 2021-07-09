from rest_framework import serializers

from datacenter.dofus2.models import Ornament


class OrnamentSerializer(serializers.ModelSerializer):

    class Meta:
        model = Ornament
        fields = '__all__'

class OrnamentLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = Ornament
        fields = [
            "id",
            "asset_id"
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        data.move_to_end('asset_id')
        return data
