from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MonsterGradeDropManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class MonsterGradeDrop(models.Model):
    monster_grade = models.ForeignKey(
        'MonsterGrade',
        on_delete=models.CASCADE,
        related_name='drops'
    )
    item = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        related_name='from_monsters',
        db_constraint=False
    )
    quantity = models.IntegerField()
    chance = models.FloatField()

    class Meta:
        ordering = ['monster_grade_id', 'chance']
        db_table = 'dofus2_monster_grade_drop'

    objects = MonsterGradeDropManager()

    def get_absolute_url(self):
        return reverse('dofus2:monstergradedrop-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.item_id)
