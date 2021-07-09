from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class IdolManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            'item_id',
            'group',
            'score'
            ).annotate(name=F(f'description_{lang}'))

class Idol(models.Model):
    id = models.AutoField(primary_key=True)
    description_fr = models.TextField(blank=True)
    description_en = models.TextField(blank=True)
    description_es = models.TextField(blank=True)
    description_pt = models.TextField(blank=True)
    description_it = models.TextField(blank=True)
    description_de = models.TextField(blank=True)
    item = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        related_name='idols'
    )
    group = models.BooleanField()
    score = models.IntegerField()

    class Meta:
        ordering = ['id']

    objects = IdolManager()

    def get_absolute_url(self):
        return reverse('dofus2:idol-detail', args=[str(self.id)])

    def __str__(self):
        return str(self.id)
