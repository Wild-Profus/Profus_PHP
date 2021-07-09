from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class NpcDialogManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(name=F(f'dialog_{lang}'))

class NpcDialog(models.Model):

    class Category(models.IntegerChoices):
        """
            Dialog Type
        """
        DIALOG = 0, _('Dialog')
        REPLY = 1, _('Reply')

    id = models.AutoField(primary_key=True)
    npc = models.ForeignKey(
        'Npc',
        on_delete=models.PROTECT,
        related_name='dialogs'
    )
    index = models.IntegerField()
    dialog_fr = models.TextField(blank=True)
    dialog_en = models.TextField(blank=True)
    dialog_es = models.TextField(blank=True)
    dialog_pt = models.TextField(blank=True)
    dialog_it = models.TextField(blank=True)
    dialog_de = models.TextField(blank=True)
    category = models.IntegerField(choices=Category.choices, default=Category.DIALOG)

    class Meta:
        db_table = 'dofus2_npc_dialog'
        ordering = ['id']

    objects = NpcDialogManager()

    def get_absolute_url(self):
        return reverse('dofus2:npc-dialog-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.dialog_fr)
