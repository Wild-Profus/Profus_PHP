from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class IdolSetIdolManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class IdolSetIdol(models.Model):
    id = models.AutoField(primary_key=True)
    idol_set = models.ForeignKey(
        'IdolSet',
        on_delete=models.CASCADE,
        related_name="idols"
    )
    idol = models.ForeignKey(
        'Idol',
        on_delete=models.CASCADE,
        related_name="sets"
    )

    class Meta:
        ordering = ['idol_id']
        db_table = 'dofus2_idol_set_idol'

    objects = IdolSetIdolManager()

    def get_absolute_url(self):
        return reverse('dofus2:idolsetidol-detail', args=[str(self.id)])

    def __str__(self):
        return str(self.id)
