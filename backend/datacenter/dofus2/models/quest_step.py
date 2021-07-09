from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class QuestStepManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(name=F(f'name_{lang}'))

class QuestStep(models.Model):
    id = models.AutoField(primary_key=True)
    quest = models.ForeignKey(
        'Quest',
        on_delete=models.PROTECT,
        related_name='steps'
    )
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    description_fr = models.TextField(blank=True)
    description_en = models.TextField(blank=True)
    description_es = models.TextField(blank=True)
    description_pt = models.TextField(blank=True)
    description_it = models.TextField(blank=True)
    description_de = models.TextField(blank=True)
    dialog = models.ForeignKey(
        'NpcDialog',
        on_delete=models.PROTECT,
        related_name='quest_steps',
        null=True
    )
    optimal_level = models.IntegerField()
    duration = models.FloatField()

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_quest_step'

    objects = QuestStepManager()

    def get_absolute_url(self):
        return reverse('dofus2:quest-step-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
