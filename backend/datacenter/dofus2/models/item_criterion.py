from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .criterion import Criterion


class ItemCriterionManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'item_id',
            ).annotate(name=F(f'name_{lang}'))

class ItemCriterion(Criterion):
    item = models.ForeignKey(
        'Item',
        on_delete=models.CASCADE,
        related_name='criteria'
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_item_criterion'
        verbose_name = "item criterion"
        verbose_name_plural = "item criteria"
