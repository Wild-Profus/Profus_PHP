from rest_framework import serializers

from datacenter.dofus2.models import Title


class TitleSerializer(serializers.ModelSerializer):

    class Meta:
        model = Title
        fields = '__all__'

class TitleLocalSerializer(serializers.ModelSerializer):

    class Meta:
        model = Title
        fields = [
            "id",
        ]

    def to_representation(self, instance):
        data = super().to_representation(instance)
        data['male_name'] = instance.male_name
        data.move_to_end('male_name')
        data['female_name'] = instance.female_name
        data.move_to_end('female_name')
        return data
