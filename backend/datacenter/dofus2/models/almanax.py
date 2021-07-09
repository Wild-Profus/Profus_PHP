from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class AlmanaxManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(name=F(f'description_{lang}'))

class Almanax(models.Model):

    id = models.AutoField(primary_key=True)
    description_fr = models.TextField(blank=True)
    description_en = models.TextField(blank=True)
    description_es = models.TextField(blank=True)
    description_pt = models.TextField(blank=True)
    description_it = models.TextField(blank=True)
    description_de = models.TextField(blank=True)
    day = models.IntegerField()
    month = models.IntegerField()
    bonus = models.IntegerField()
    meryde = models.ForeignKey(
        'Npc',
        on_delete=models.PROTECT,
        related_name='almanax_quests'
    )
    offering = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        related_name='almanax_quests'
    )
    quantity = models.IntegerField()
    kamas_reward = models.IntegerField()

    class Meta:
        ordering = ['id']

    objects = AlmanaxManager()

    def get_absolute_url(self):
        return reverse('dofus2:almanax-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
