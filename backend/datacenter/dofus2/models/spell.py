from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class SpellManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'icon_id')\
            .annotate(name=F(f'name_{lang}'), description=F(f'description_{lang}'))

class Spell(models.Model):
    id = models.BigIntegerField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    description_fr = models.TextField(blank=True)
    description_en = models.TextField(blank=True)
    description_es = models.TextField(blank=True)
    description_pt = models.TextField(blank=True)
    description_it = models.TextField(blank=True)
    description_de = models.TextField(blank=True)
    icon_id = models.IntegerField(null=True)

    class Meta:
        ordering = ['id']

    objects = SpellManager()

    def get_absolute_url(self):
        return reverse('dofus2:spell-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
