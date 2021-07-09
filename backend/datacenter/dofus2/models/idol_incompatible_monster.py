from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class IdolIncompatibleMonsterManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class IdolIncompatibleMonster(models.Model):
    id = models.AutoField(primary_key=True)
    idol = models.ForeignKey(
        'Idol',
        on_delete=models.PROTECT,
        related_name='incompatible_monsters'
    )
    monster = models.ForeignKey(
        'Monster',
        on_delete=models.PROTECT,
        related_name='incompatible_idols',
        db_constraint=False
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_idol_incompatible_monster'


    objects = IdolIncompatibleMonsterManager()

    def get_absolute_url(self):
        return reverse('dofus2:idolincompatiblemonster-detail', args=[str(self.id)])

    def __str__(self):
        return self.id
