from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class ItemManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            'category_id',
            'icon',
            'level',
            'weight',
            'cursed',
            'etheral',
            'tradable',
            'pnj_price',
            'criteria'
            ).annotate(name=F(f'name_{lang}'))

class Item(models.Model):
    id = models.AutoField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    category = models.ForeignKey(
        'ItemCategory',
        on_delete=models.PROTECT,
        related_name='items'
    )
    icon_id = models.IntegerField()
    level = models.IntegerField()
    weight = models.IntegerField()
    pnj_price = models.IntegerField()
    item_set = models.ForeignKey(
        'ItemSet',
        on_delete=models.PROTECT,
        null=True
    )
    food_xp = models.DecimalField(max_digits=25, decimal_places=12)
    nuggets = models.DecimalField(max_digits=25, decimal_places=12)

    class Meta:
        ordering = ['-level']

    objects = ItemManager()

    def get_absolute_url(self):
        return reverse('dofus2:item-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
