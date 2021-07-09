from django.db import models
from django.urls import reverse
from django.utils.translation import gettext_lazy as _
from django.utils import timezone

class ItemPriceManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, category, server):
    #     return super().get_queryset()
    # def get_queryset(self, category, server):
        return super().get_queryset().only(
            "item_id",
            "price",
            "creation_date",
            ).filter(
                server_id=server,
                category=category
            ).latest(
                'creation_date'
            )

class ItemPrice(models.Model):
    class Category(models.IntegerChoices):
        """
            Item Price Type Category
        """
        AVERAGE_PER_UNIT = 0, _('Average')
        HDV_PER_UNIT = 1, 'x1'
        HDV_PER_TEN = 2, 'x10'
        HDV_PER_HUNDRED = 3, 'x100'

    id = models.BigAutoField(primary_key=True)
    server = models.ForeignKey(
        'Server',
        on_delete=models.CASCADE,
        related_name='servers'
    )
    user = models.ForeignKey(
        'users.User',
        on_delete=models.SET_NULL,
        null=True,
        default=None,
        related_name='users'
    )
    item = models.ForeignKey(
        'Item',
        on_delete=models.PROTECT,
        related_name='items',
        db_constraint=False
    )
    creation_date = models.DateTimeField(default=timezone.now)
    category = models.IntegerField(choices=Category.choices, default=Category.choices[0][0])
    price = models.IntegerField()

    class Meta:
        ordering = ['-creation_date']
        db_table = 'dofus2_item_price'

    objects = ItemPriceManager()

    def get_absolute_url(self):
        return reverse('dofus2:item-price-detail', args=[str(self.id)])

    def __str__(self):
        return _('price')
