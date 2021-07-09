from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class SpellLevelManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class SpellLevel(models.Model):
    id = models.AutoField(primary_key=True)
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.CASCADE,
        related_name='grades'
    )
    grade = models.IntegerField()
    ap_cost = models.IntegerField()
    min_range = models.IntegerField()
    range = models.IntegerField()
    range_alterable = models.BooleanField()
    cast_in_line = models.BooleanField()
    cast_in_diagonal = models.BooleanField()
    critical_chance = models.IntegerField()
    max_stack = models.IntegerField()
    cast_per_turn = models.IntegerField()
    cast_per_target = models.IntegerField()
    cast_interval = models.IntegerField()
    start_cooldown = models.IntegerField()
    global_cooldown = models.IntegerField()
    min_level = models.IntegerField(null=True)

    class Meta:
        ordering = ['spell_id', 'grade']
        db_table = 'dofus2_spell_level'

    objects = SpellLevelManager()

    def get_absolute_url(self):
        return reverse('dofus2:spell-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
