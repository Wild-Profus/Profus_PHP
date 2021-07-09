"""
    Abstract Criterion Class
"""
from django.db import models
from django.utils.translation import gettext_lazy as _


class Criterion(models.Model):
    """
        Base Criterion Model class
    """
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)

    class Meta:
        abstract = True
