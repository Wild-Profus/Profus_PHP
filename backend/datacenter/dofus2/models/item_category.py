from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class ItemCategoryManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id', 'category', 'sort', 'effect_area_id')\
            .annotate(name=F(f'name_{lang}'))

class ItemCategory(models.Model):

    class Category(models.IntegerChoices):
        """
            Item Type Category
        """
        COLLAR = 1, _('COLLAR')
        WEAPON = 2, _('WEAPON')
        RING = 3, _('RING')
        BELT = 4, _('BELT')
        SHOES = 5, _('SHOES')
        CONSUMABLE = 6, _('CONSUMABLE')
        SHIELD = 7, _('SHIELD')
        CATCHING_ITEMS = 8, _('CATCHING ITEMS')
        RESOURCES = 9, _('RESOURCES')
        HELMET = 10, _('HELMET')
        CAPE = 11, _('CAPE')
        PET = 12, _('PET')
        DOFUS_TROPHY = 13, _('DOFUS TROPHY')
        QUEST_ITEMS = 14, _('QUEST_ITEMS')
        MUTATIONS = 15, _('MUTATIONS')
        FOODS = 16, _('FOODS')
        BLESSINGS = 17, _('BLESSINGS')
        CURSES = 18, _('CURSES')
        ROLEPLAY_BUFFS = 19, _('ROLEPLAY BUFFS')
        FOLLOWERS = 20, _('FOLLOWERS')
        MOUNTS = 21, _('MOUNTS')
        LIVING_ITEMS = 22, _('LIVING ITEMS')
        COMPANION = 23, _('COMPANION')
        MOUNT_EQUIPMENT = 24, _('MOUNT EQUIPMENT')
        COSTUME = 25, _('COSTUME')
        CERTIFICATE = 27, _('CERTIFICATE')
        PET_GHOST = 28, _('PET GHOST')

    class Sort(models.IntegerChoices):
        """
            Item Type Sort
        """
        EQUIPMENT = 1, _('Equipment')
        CONSUMABLE = 2, _('Consumable')
        RESOURCE = 3, _('Resource')
        QUEST = 4, _('Quest')

    id = models.AutoField(primary_key=True)
    name_fr = models.TextField(blank=True)
    name_en = models.TextField(blank=True)
    name_es = models.TextField(blank=True)
    name_pt = models.TextField(blank=True)
    name_it = models.TextField(blank=True)
    name_de = models.TextField(blank=True)
    category = models.IntegerField(choices=Category.choices)
    sort = models.IntegerField(choices=Sort.choices)
    effect_area = models.ForeignKey(
        'EffectArea',
        on_delete=models.PROTECT,
        related_name='weapon_types',
        null=True
    )

    class Meta:
        ordering = ['sort', 'category']
        db_table = 'dofus2_item_category'
        verbose_name = "item category"
        verbose_name_plural = "item categories"

    objects = ItemCategoryManager()

    def get_absolute_url(self):
        return reverse('dofus2:itemcategory-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.name_fr)
