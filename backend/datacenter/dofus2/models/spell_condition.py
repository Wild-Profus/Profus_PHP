from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class SpellConditionManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class SpellCondition(models.Model):

    class Condition(models.IntegerChoices):
        FORBIDDEN = 0, _('Forbidden')
        REQUIRED = 1, _('Required')
        AUTHORIZED = 2, _('Authorized')

    id = models.AutoField(primary_key=True)
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.CASCADE,
        related_name='conditions'
    )
    spell_state = models.ForeignKey(
        'SpellState',
        on_delete=models.PROTECT,
        related_name='spells'
    )
    condition = models.IntegerField(
        choices=Condition.choices,
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_spell_condition'

    objects = SpellConditionManager()

    def get_absolute_url(self):
        return reverse('dofus2:mount-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
