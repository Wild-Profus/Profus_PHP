from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MonsterGradeManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class MonsterGrade(models.Model):
    id = models.AutoField(primary_key=True)
    grade = models.IntegerField()
    level = models.IntegerField()
    life_points = models.IntegerField()
    action_points = models.IntegerField()
    movement_points = models.IntegerField()
    vitality = models.IntegerField()
    pa_dodge = models.IntegerField()
    pm_dodge = models.IntegerField()
    wisdom = models.IntegerField()
    earth_resistance = models.IntegerField()
    air_resistance = models.IntegerField()
    fire_resistance = models.IntegerField()
    water_resistance = models.IntegerField()
    neutral_resistance = models.IntegerField()
    xp = models.IntegerField()
    damage_reflect = models.IntegerField()
    hidden_level = models.IntegerField()
    strength = models.IntegerField()
    intelligence = models.IntegerField()
    chance = models.IntegerField()
    agility = models.IntegerField()
    bonus_range = models.IntegerField()
    monster = models.ForeignKey(
        'Monster',
        on_delete=models.CASCADE,
        related_name='grades'
    )
    starting_spell = models.ForeignKey(
        'Spell',
        on_delete=models.PROTECT,
        related_name='monster_starters',
        null=True,
        db_constraint=False
    )

    class Meta:
        ordering = ['monster_id', 'grade']
        db_table = 'dofus2_monster_grade'

    objects = MonsterGradeManager()

    def get_absolute_url(self):
        return reverse('dofus2:monstergarde-detail', args=[str(self.id)])

    def __str__(self):
        return self.grade
