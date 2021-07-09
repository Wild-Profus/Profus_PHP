from rest_framework import serializers

from datacenter.dofus2.models import Emote


class EmoteSerializer(serializers.ModelSerializer):

    class Meta:
        model = Emote
        fields = '__all__'

class EmoteLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = Emote
        fields = [
            "id",
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['name'] = instance.name
        data.move_to_end('name')
        data['cmd'] = instance.cmd
        data.move_to_end('cmd')
        return data
