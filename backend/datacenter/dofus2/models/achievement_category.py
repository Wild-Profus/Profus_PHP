from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class AchievementCategoryManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'category', 'sort', 'effect_area_id')\
            .annotate(name=F(f'name_{lang}'))

class AchievementCategory(models.Model):
    id = models.AutoField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    parent = models.ForeignKey(
        'self',
        on_delete=models.PROTECT,
        related_name='childs',
        null=True
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_achievement_category'
        verbose_name = "achievement category"
        verbose_name_plural = "achievement categories"

    objects = AchievementCategoryManager()

    def get_absolute_url(self):
        return reverse('dofus2:achievement-category-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
