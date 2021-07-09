from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class SkillManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'super_race_id')\
            .annotate(name=F(f'name_{lang}'))

class Skill(models.Model):
    id = models.AutoField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    job = models.ForeignKey(
        'job',
        on_delete=models.CASCADE,
        related_name='skills'
    )
    collectable = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        related_name='source_skill',
        null=True
    )
    min_level = models.IntegerField()

    class Meta:
        ordering = ['id']

    objects = SkillManager()

    def get_absolute_url(self):
        return reverse('dofus2:job-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
