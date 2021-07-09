from django.contrib.auth import get_user_model
from django.contrib.auth.forms \
    import UserCreationForm as BaseUserCreationForm,\
    UserChangeForm as BaseUserChangeForm,\
    AdminPasswordChangeForm as BaseAdminPasswordChangeForm


class UserCreationForm(BaseUserCreationForm):
    class Meta:
        model = get_user_model()
        fields = ('email', 'pseudo')


class UserChangeForm(BaseUserChangeForm):
    class Meta:
        model = get_user_model()
        fields = ('email', 'pseudo')

class AdminPasswordChangeForm(BaseAdminPasswordChangeForm):
    class Meta:
        model = get_user_model()
        fields = ('email', 'pseudo')
