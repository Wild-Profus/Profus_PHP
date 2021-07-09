"""
    Abstract Effect Class
"""
from django.db import models
from django.utils.translation import gettext_lazy as _


class Effect(models.Model):
    """
        Base Effect Model class
    """

    class Element(models.IntegerChoices):
        """
            Effect Element
        """
        ELEMENT_MULTI = -2, _('ELEMENT_MULTI')
        ELEMENT_UNDEFINED = -1, _('ELEMENT_UNDEFINED')
        ELEMENT_NEUTRAL = 0, _('ELEMENT_NEUTRAL')
        ELEMENT_EARTH = 1, _('ELEMENT_EARTH')
        ELEMENT_FIRE = 2, _('ELEMENT_FIRE')
        ELEMENT_WATER = 3, _('ELEMENT_WATER')
        ELEMENT_AIR = 4, _('ELEMENT_AIR')
        ELEMENT_NONE = 5, _('ELEMENT_NONE')
        ELEMENT_BEST = 6, _('ELEMENT_BEST')

    id = models.BigAutoField(primary_key=True)
    effect_id = models.IntegerField()
    name_fr = models.CharField(max_length=256, blank=True)
    name_en = models.CharField(max_length=256, blank=True)
    name_es = models.CharField(max_length=256, blank=True)
    name_pt = models.CharField(max_length=256, blank=True)
    name_it = models.CharField(max_length=256, blank=True)
    name_de = models.CharField(max_length=256, blank=True)
    effect_priority = models.IntegerField()
    element = models.IntegerField(
        choices=Element.choices,
        default=Element.ELEMENT_UNDEFINED
    )
    characteristic = models.ForeignKey(
        'Characteristic',
        on_delete=models.PROTECT,
        null=True
    )
    value = models.IntegerField(null=True)

    class Meta:
        abstract = True
