from rest_framework import serializers

from ..models import Characteristic


class CharacteristicSerializer(serializers.ModelSerializer):

    class Meta:
        model = Characteristic
        fields = '__all__'

class CharacteristicLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = Characteristic
        fields = [
            "id",
            "category",
            "weight",
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        data.move_to_end('name', last=False)
        return data
