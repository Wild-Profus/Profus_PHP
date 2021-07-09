from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class CharacteristicManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'category', 'weight')\
            .annotate(name=F(f'name_{lang}'))

class Characteristic(models.Model):

    class Category(models.IntegerChoices):
        """
            Dofus client version
        """
        PRIMARY = 0, _('Primary')
        SECONDARY = 1, _('Secondary')
        DAMAGE = 2, _('Damage')
        RESISTANCE = 3, _('Resistance')

    id = models.AutoField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    weight = models.IntegerField(null=True)
    category = models.IntegerField(
        null=True,
        blank=True,
        choices=Category.choices,
        default=None
    )

    class Meta:
        ordering = ['category', '-weight', 'id']

    objects = CharacteristicManager()

    def get_absolute_url(self):
        return reverse('dofus2:characteristic-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
