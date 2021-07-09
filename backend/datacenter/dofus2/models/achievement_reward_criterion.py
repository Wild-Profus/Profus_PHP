from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .criterion import Criterion

class AchievementRewardCriterionManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id'
            ).annotate(name=F(f'name_{lang}'))

class AchievementRewardCriterion(Criterion):
    achievement_reward = models.ForeignKey(
        'AchievementReward',
        on_delete=models.PROTECT,
        related_name='criteria'
    )

    class Meta:
        ordering = ['achievement_reward_id']
        db_table = 'dofus2_achievement_reward_criterion'
        verbose_name = "achievement reward criterion"
        verbose_name_plural = "achievement reward criteria"

    objects = AchievementRewardCriterionManager()

    def get_absolute_url(self):
        return reverse('dofus2:achievement-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
