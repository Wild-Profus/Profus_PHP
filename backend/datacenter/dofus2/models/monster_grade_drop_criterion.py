from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .criterion import Criterion


class MonsterGradeDropCriterionManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'drop_id',
            ).annotate(name=F(f'name_{lang}'))

class MonsterGradeDropCriterion(Criterion):
    drop = models.ForeignKey(
        'MonsterGradeDrop',
        on_delete=models.CASCADE,
        related_name='criteria'
    )

    objects = MonsterGradeDropCriterionManager

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_monster_grade_drop_criterion'
        verbose_name = "monster grade drop criterion"
        verbose_name_plural = "monster grade drop criteria"
