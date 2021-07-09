from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .effect import Effect


class ItemSetEffectManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            ).annotate(name=F(f'name_{lang}'))

class ItemSetEffect(Effect):
    item_set = models.ForeignKey(
        'ItemSet',
        on_delete=models.CASCADE,
        related_name='effects'
    )
    item_count = models.IntegerField()
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
        related_name='+'
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_item_set_effect'
