from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class RecipeElementManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class RecipeElement(models.Model):
    recipe = models.ForeignKey(
        'Recipe',
        on_delete=models.CASCADE,
        related_name='ingredients'
    )
    item = models.ForeignKey(
        'Item',
        on_delete=models.CASCADE,
        related_name='related_recipes'
    )
    quantity = models.IntegerField()

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_recipe_element'

    objects = RecipeElementManager()

    def get_absolute_url(self):
        return reverse('dofus2:item-detail', args=[str(self.item_id)])

    def __str__(self):
        return _(self.id)
