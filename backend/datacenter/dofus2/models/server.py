from django.db import models
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class ServerManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().all()

class Server(models.Model):

    class GameMode(models.IntegerChoices):
        """
            Server game mode
        """
        NORMAL = 0, _('Normal')
        HARDCORE = 1, _('Hardcore')
        PVM_HARDCORE = 2, _('PVM Hardcore')
        TEMPORIS = 3, 'Temporis'
        BETA = 4, 'Beta'
        UNOFFICIAL = 5, _('Non official')

    class Flag(models.TextChoices):
        """
            Server main community flag
        """
        FR = 'fr'
        EN = 'en'
        ES = 'es'
        PT = 'pt'
        IT = 'it'
        DE = 'de'

    id = models.BigIntegerField(primary_key=True)
    name = models.CharField(max_length=64)
    active = models.BooleanField(default=True)
    game_mode = models.IntegerField(choices=GameMode.choices, default=GameMode.NORMAL)
    mono = models.BooleanField(default=False)
    flag = models.CharField(max_length=3, choices=Flag.choices)

    objects = ServerManager()

    def __str__(self):
        return self.name

    def get_absolute_url(self):
        return reverse('dofus2:server-detail', args=[str(self.id)])
