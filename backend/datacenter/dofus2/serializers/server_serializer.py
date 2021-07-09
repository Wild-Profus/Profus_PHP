from rest_framework import serializers

from datacenter.dofus2.models import Server


class ServerSerializer(serializers.ModelSerializer):

    class Meta:
        model = Server
        fields = '__all__'
