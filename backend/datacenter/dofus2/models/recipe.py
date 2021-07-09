from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class RecipeManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class Recipe(models.Model):
    result = models.OneToOneField(
        'Item',
        on_delete=models.CASCADE,
        related_name='recipes',
        primary_key=True
    )
    job = models.ForeignKey(
        'job',
        on_delete=models.CASCADE,
        related_name='recipes'
    )
    skill = models.ForeignKey(
        'Skill',
        on_delete=models.CASCADE,
        related_name='recipes'
    )
    xp = models.IntegerField()

    class Meta:
        ordering = ['result_id']

    objects = RecipeManager()

    def get_absolute_url(self):
        return reverse('dofus2:job-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
