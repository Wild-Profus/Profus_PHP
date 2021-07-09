from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class EmoteManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(name=F(f'name_{lang}'), cmd=F(f'cmd_{lang}'))

class Emote(models.Model):
    id = models.BigIntegerField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    cmd_fr = models.TextField(blank=True)
    cmd_en = models.TextField(blank=True)
    cmd_es = models.TextField(blank=True)
    cmd_pt = models.TextField(blank=True)
    cmd_it = models.TextField(blank=True)
    cmd_de = models.TextField(blank=True)

    class Meta:
        ordering = ['id']

    objects = EmoteManager()

    def get_absolute_url(self):
        return reverse('dofus2:emote-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
