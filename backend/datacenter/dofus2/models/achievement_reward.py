from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class AchievementRewardManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id'
            ).annotate(name=F(f'name_{lang}'))

class AchievementReward(models.Model):

    class Type(models.IntegerChoices):
        NONE = 0, _('None')
        ITEM = 1, _('Item')
        EMOTE = 2, _('Emote')
        SPELL = 3, _('Spell')
        TITLE = 4, _('Title')
        ORNAMENT = 5, _('Ornament')

    id = models.AutoField(primary_key=True)
    achievement = models.ForeignKey(
        'Achievement',
        on_delete=models.PROTECT,
        related_name='rewards'
    )
    type = models.IntegerField(choices=Type.choices)
    kamas_ratio = models.FloatField()
    experience_ratio = models.FloatField()
    scaling = models.BooleanField(default=False)
    item = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        null=True
    )
    item_quantity = models.IntegerField()
    emote = models.ForeignKey(
        'Emote',
        on_delete=models.PROTECT,
        null=True
    )
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.PROTECT,
        null=True
    )
    title = models.ForeignKey(
        'Title',
        on_delete=models.PROTECT,
        null=True
    )
    ornament = models.ForeignKey(
        'Ornament',
        on_delete=models.PROTECT,
        null=True
    )

    class Meta:
        ordering = ['achievement_id']
        db_table = 'dofus2_achievement_reward'

    objects = AchievementRewardManager()

    def get_absolute_url(self):
        return reverse('dofus2:achievement-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
