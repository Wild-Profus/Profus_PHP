from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class AchievementManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only(
            'id'
            ).annotate(name=F(f'name_{lang}'))

class Achievement(models.Model):
    id = models.AutoField(primary_key=True)
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
    category = models.ForeignKey(
        'AchievementCategory',
        on_delete=models.PROTECT,
        related_name='achievements'
    )
    icon_id = models.IntegerField()
    level = models.IntegerField()
    points = models.IntegerField()
    account_linked = models.BooleanField()

    class Meta:
        ordering = ['-points', '-level']

    objects = AchievementManager()

    def get_absolute_url(self):
        return reverse('dofus2:achievement-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
