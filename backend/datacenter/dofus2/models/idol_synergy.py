from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class IdolSynergyManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class IdolSynergy(models.Model):
    id = models.AutoField(primary_key=True)
    idol = models.ForeignKey(
        'Idol',
        on_delete=models.PROTECT,
        related_name='synergies'
    )
    synergy_idol = models.ForeignKey(
        'Idol',
        on_delete=models.PROTECT,
        related_name='+',
        db_constraint=False
    )
    coef = models.FloatField()

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_idol_synergy'
        verbose_name = "idol synergy"
        verbose_name_plural = "idol synergies"


    objects = IdolSynergyManager()

    def get_absolute_url(self):
        return reverse('dofus2:idol-detail', args=[str(self.id)])

    def __str__(self):
        return str(self.id)
