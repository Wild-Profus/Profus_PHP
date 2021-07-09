from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MonsterSpellManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class MonsterSpell(models.Model):
    monster = models.ForeignKey(
        'Monster',
        on_delete=models.CASCADE
    )
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.PROTECT
    )

    class Meta:
        ordering = ['monster_id', 'spell_id']
        db_table = 'dofus2_monster_spell'

    objects = MonsterSpellManager()

    def get_absolute_url(self):
        return reverse('dofus2:monsterspell-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
