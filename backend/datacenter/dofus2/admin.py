from django.contrib import admin
from . import models

@admin.register(models.Server)
class ServerAdmin(admin.ModelAdmin):
    pass
