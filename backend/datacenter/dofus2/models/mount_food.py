from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MountFoodManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class MountFood(models.Model):
    id = models.AutoField(primary_key=True)
    mount_category = models.ForeignKey(
        'MountCategory',
        on_delete=models.CASCADE
    )
    item_type = models.ForeignKey(
        'ItemCategory',
        on_delete=models.CASCADE
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_mount_food'

    objects = MountFoodManager()

    def get_absolute_url(self):
        return reverse('dofus2:mountfood-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
