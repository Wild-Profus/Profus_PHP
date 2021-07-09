from django.db import models
from django.db.models import F
from django.urls import reverse
from django.utils.translation import gettext_lazy as _


class NpcMessageManager(models.Manager):
    # def get_queryset(self):
    #     return super().get_queryset().all()

    def local_only(self, lang):
        return super().get_queryset().only('id')\
            .annotate(name=F(f'message_{lang}'))

class NpcMessage(models.Model):
    id = models.AutoField(primary_key=True)
    message_id = models.IntegerField()
    message_fr = models.TextField(blank=True)
    message_en = models.TextField(blank=True)
    message_es = models.TextField(blank=True)
    message_pt = models.TextField(blank=True)
    message_it = models.TextField(blank=True)
    message_de = models.TextField(blank=True)

    class Meta:
        db_table = 'dofus2_npc_message'
        ordering = ['id']

    objects = NpcMessageManager()

    def get_absolute_url(self):
        return reverse('dofus2:npc-message-detail', args=[str(self.id)])

    def __str__(self):
        return _(self.message_fr)
