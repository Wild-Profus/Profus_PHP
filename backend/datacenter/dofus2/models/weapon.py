from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class WeaponManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'item_id',
            ).annotate(name=F(f'name_{lang}'))

class Weapon(models.Model):
    item = models.OneToOneField(
        'Item',
        on_delete=models.CASCADE,
        primary_key=True
    )
    ap_cost = models.IntegerField()
    min_range = models.IntegerField(null=True)
    range = models.IntegerField(null=True)
    casts_per_turn = models.IntegerField(null=True)
    critical_hit_chance = models.IntegerField(null=True)
    critical_damage = models.IntegerField(null=True)

    class Meta:
        ordering = ['-ap_cost']

    objects = WeaponManager()

    def get_absolute_url(self):
        return reverse('dofus2:weapon-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.item.name_fr)
