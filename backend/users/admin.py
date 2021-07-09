from django.contrib import admin
from django.contrib.auth import get_user_model
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from django.utils.translation import gettext_lazy as _

from .forms import UserChangeForm, UserCreationForm, AdminPasswordChangeForm

class UserAdmin(BaseUserAdmin):
    add_form_template = 'admin/auth/user/add_form.html'
    change_user_password_template = None
    model = get_user_model()
    fieldsets = (
        (None, {'fields': ('pseudo', 'password')}),
        (_('Personal info'), {'fields': ('email',)}),
        (_('Permissions'), {
            'fields': ('is_active', 'is_staff', 'is_superuser', 'is_trusted', 'is_restricted', 'groups', 'user_permissions'),
        }),
        (_('Important dates'), {'fields': ('last_login', 'date_joined')}),
    )
    add_fieldsets = (
        (None, {
            'classes': ('wide',),
            'fields': ('pseudo', 'password1', 'password2'),
        }),
    )
    form = UserChangeForm
    add_form = UserCreationForm
    change_password_form = AdminPasswordChangeForm
    list_display = [
        'pseudo',
        'email',
        'is_staff'
    ]
    list_filter = ('is_staff', 'is_superuser', 'is_active', 'is_trusted', 'is_restricted', 'groups')
    search_fields = ('pseudo', 'email')
    ordering = ('pseudo',)
    filter_horizontal = ('groups', 'user_permissions',)

admin.site.register(get_user_model(), UserAdmin)
