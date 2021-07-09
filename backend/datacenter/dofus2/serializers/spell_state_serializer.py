from rest_framework import serializers

from datacenter.dofus2.models import SpellState


class SpellStateSerializer(serializers.ModelSerializer):

    class Meta:
        model = SpellState
        fields = '__all__'

class SpellStateLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = SpellState
        fields = [
            'id',
            'icon_id',
            'silent',
            'incurable',
            'invulnerability',
            'prevents',
            'takle',
            'movement'
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        return data
