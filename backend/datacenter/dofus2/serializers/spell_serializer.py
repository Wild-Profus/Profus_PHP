from rest_framework import serializers

from datacenter.dofus2.models import Spell


class SpellSerializer(serializers.ModelSerializer):

    class Meta:
        model = Spell
        fields = '__all__'

class SpellLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = Spell
        fields = [
            "id",
            "icon_id"
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        data['description'] = instance.description
        return data
