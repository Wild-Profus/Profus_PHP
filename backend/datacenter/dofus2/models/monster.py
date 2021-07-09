from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class MonsterManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'category_id', 'look', 'certificate_id')\
            .annotate(name=F(f'name_{lang}'))

class Monster(models.Model):
    class Type(models.IntegerChoices):
        NORMAL = 0, _('Normal')
        BOSS = 1, _('Boss')
        UNIQUE = 2, _('Unique')

    id = models.AutoField(primary_key=True)
    race = models.ForeignKey(
        'MonsterRace',
        on_delete=models.PROTECT,
        db_constraint=False
    )
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    gfx = models.TextField(blank=True)
    look = models.TextField(blank=True, null=True)
    type = models.IntegerField(choices=Type.choices, default=Type.NORMAL)
    idols_allowed = models.BooleanField(default=True)
    dares_allowed = models.BooleanField(default=True)
    pushable = models.BooleanField(default=True)
    carriable = models.BooleanField(default=True)
    transposable = models.BooleanField(default=True)
    portalable = models.BooleanField(default=True)

    class Meta:
        ordering = ['race_id', 'id']

    objects = MonsterManager()

    def get_absolute_url(self):
        return reverse('dofus2:monster-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
