from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .effect import Effect


class SpellStateEffectManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            ).annotate(name=F(f'name_{lang}'))

class SpellStateEffect(Effect):
    spell_state = models.ForeignKey(
        'SpellState',
        on_delete=models.CASCADE,
        related_name='effects'
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_spell_state_effect'
