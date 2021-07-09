from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .effect import Effect


class ItemEffectManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            ).annotate(name=F(f'name_{lang}'))

class ItemEffect(Effect):
    min_value = models.IntegerField(null=True)
    item = models.ForeignKey(
        'Item',
        on_delete=models.CASCADE,
        related_name='effects'
    )
    title = models.ForeignKey(
        'Title',
        on_delete=models.PROTECT,
        null=True,
        related_name='+'
    )
    emote = models.ForeignKey(
        'Emote',
        on_delete=models.PROTECT,
        null=True,
        related_name='+'
    )
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.PROTECT,
        null=True,
        related_name='+',
        db_constraint=False
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_item_effect'
