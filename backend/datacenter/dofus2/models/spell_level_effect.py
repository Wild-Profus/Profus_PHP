from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _

from .effect import Effect

class SpellLevelEffectManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id',
            ).annotate(name=F(f'name_{lang}'))

class SpellLevelEffect(Effect):
    id = models.AutoField(primary_key=True)
    spell_level = models.ForeignKey(
        'SpellLevel',
        on_delete=models.CASCADE,
        related_name='effects'
    )
    spell_state = models.ForeignKey(
        'SpellState',
        on_delete=models.PROTECT,
        null=True,
        related_name='+'
    )
    monster = models.ForeignKey(
        'Monster',
        on_delete=models.PROTECT,
        null=True,
        related_name='+'
    )
    min_value = models.IntegerField(null=True)
    critical = models.BooleanField(default=False)
    order = models.IntegerField(null=True)
    target_mask = models.CharField(max_length=128)
    duration = models.IntegerField(null=True)
    delay = models.IntegerField(null=True)
    random = models.IntegerField(null=True)
    group = models.IntegerField(null=True)
    triggers = models.CharField(max_length=128)
    zone_size = models.IntegerField(null=True)
    zone_shape = models.IntegerField(null=True)
    zone_min_size = models.IntegerField(null=True)
    zone_efficiency_percent = models.IntegerField(null=True)
    zone_max_efficiency = models.IntegerField(null=True)
    zone_stop_at_target = models.IntegerField(null=True)
    min_level = models.IntegerField(null=True)

    class Meta:
        ordering = ['spell_level_id', 'critical']
        db_table = 'dofus2_spell_level_effect'

    objects = SpellLevelEffectManager()

    def get_absolute_url(self):
        return reverse('dofus2:spell-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
