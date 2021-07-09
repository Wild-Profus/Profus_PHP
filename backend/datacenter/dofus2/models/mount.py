from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MountManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'category_id', 'look', 'certificate_id')\
            .annotate(name=F(f'name_{lang}'))

class Mount(models.Model):
    id = models.AutoField(primary_key=True)
    category = models.ForeignKey(
        'MountCategory',
        on_delete=models.PROTECT
    )
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    look = models.CharField(max_length=64)
    certificate = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT
    )

    class Meta:
        ordering = ['category_id', 'id']

    objects = MountManager()

    def get_absolute_url(self):
        return reverse('dofus2:mount-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
