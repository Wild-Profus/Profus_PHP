from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class SpellStateManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'icon_id', 'silent', 'incurable', 'invulnerability', 'prevents', 'takle', 'movement')\
            .annotate(name=F(f'name_{lang}'))

class SpellState(models.Model):

    class Invulnerability(models.IntegerChoices):
        NONE = 0, _('None')
        MELEE = 1, _('Melee')
        RANGE = 2, _('Range')
        IMMUNITY = 3, _('Immunity')

    class TakleRestriction(models.IntegerChoices):
        NONE = 0, _('None')
        NO_TAKLE = 1, _('No Takle')
        ESCAPE_ALL = 2, _('Escape all')
        IGNORE = 3, _('Ignore')

    class ForbiddenAction(models.IntegerChoices):
        NONE = 0, _('None')
        SPELL = 1, _('Spell')
        FIGHT = 2, _('Fight')
        BOTH = 3, _('Both')
        DAMAGE = 4, _('Damage')

    class MovementRestriction(models.IntegerChoices):
        NONE = 0, _('None')
        MOVE_ONLY = 1, _('Move')
        PUSH_ONLY = 2, _('Push')
        SWITCH_ONLY = 3, _('Switch')
        PUSH_AND_MOVE = 4, _('Push nor Move')
        MOVE_AND_SWITCH = 5, _('Move nor Switch')
        PUSH_AND_SWITCH = 6, _('Push nor Switch')
        ALL = 7, _('All')

    id = models.BigIntegerField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    icon_id = models.CharField(max_length=64, blank=True, null=True)
    silent = models.BooleanField()
    incurable = models.BooleanField()
    invulnerability = models.IntegerField(
        choices=Invulnerability.choices,
        default=Invulnerability.NONE
    )
    prevents = models.IntegerField(
        choices=ForbiddenAction.choices,
        default=ForbiddenAction.NONE
    )
    takle = models.IntegerField(
        choices=TakleRestriction.choices,
        default=TakleRestriction.NONE
    )
    movement = models.IntegerField(
        choices=MovementRestriction.choices,
        default=MovementRestriction.NONE
    )

    class Meta:
        ordering = ['id']
        db_table = 'dofus2_spell_state'

    objects = SpellStateManager()

    def get_absolute_url(self):
        return reverse('dofus2:spellstate-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
