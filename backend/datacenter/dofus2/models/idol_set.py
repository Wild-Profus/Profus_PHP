from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class IdolSetManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class IdolSet(models.Model):
    id = models.AutoField(primary_key=True)
    score = models.IntegerField()

    class Meta:
        ordering = ['-score']
        db_table = 'dofus2_idol_set'

    objects = IdolSetManager()

    def get_absolute_url(self):
        return reverse('dofus2:idolset-detail', args=[str(self.id)])

    def __str__(self):
        return self.id
