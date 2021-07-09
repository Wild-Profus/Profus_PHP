from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class CharacterClassSpellManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset()

class CharacterClassSpell(models.Model):

    character_class = models.ForeignKey(
        'CharacterClass',
        on_delete=models.CASCADE
    )
    spell = models.ForeignKey(
        'Spell',
        on_delete=models.PROTECT
    )
    index = models.IntegerField()

    class Meta:
        ordering = ['character_class_id', 'index']
        db_table = 'dofus2_character_class_spell'

    objects = CharacterClassSpellManager()

    def get_absolute_url(self):
        return reverse('dofus2:characterclassspell-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.id)
