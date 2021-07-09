from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class TitleManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(male_name=F(f'male_name_{lang}'), female_name=F(f'female_name_{lang}'))

class Title(models.Model):
    id = models.BigIntegerField(primary_key=True)
    male_name_fr = models.TextField(blank=True)
    male_name_en = models.TextField(blank=True)
    male_name_es = models.TextField(blank=True)
    male_name_pt = models.TextField(blank=True)
    male_name_it = models.TextField(blank=True)
    male_name_de = models.TextField(blank=True)
    female_name_fr = models.TextField(blank=True)
    female_name_en = models.TextField(blank=True)
    female_name_es = models.TextField(blank=True)
    female_name_pt = models.TextField(blank=True)
    female_name_it = models.TextField(blank=True)
    female_name_de = models.TextField(blank=True)

    class Meta:
        ordering = ['id']

    objects = TitleManager()

    def get_absolute_url(self):
        return reverse('dofus2:title-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.male_name_fr)
