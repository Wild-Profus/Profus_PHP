from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .criterion import Criterion

class AchievementObjectiveCriterionManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id'
            ).annotate(name=F(f'name_{lang}'))

class AchievementObjectiveCriterion(Criterion):
    achievement_objective = models.ForeignKey(
        'AchievementObjective',
        on_delete=models.PROTECT,
        related_name='criteria'
    )

    class Meta:
        ordering = ['achievement_objective_id']
        db_table = 'dofus2_achievement_objective_criterion'
        verbose_name = "achievement objective criterion"
        verbose_name_plural = "achievement objective criteria"

    objects = AchievementObjectiveCriterionManager()

    def get_absolute_url(self):
        return reverse('dofus2:achievement-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
