"""
WSGI config for kali project.

It exposes the WSGI callable as a module-level variable named ``application``.

For more information on this file, see
https://docs.djangoproject.com/en/3.0/howto/deployment/wsgi/
"""
import os
import sys

from django.core.wsgi import get_wsgi_application


# os.environ.setdefault('DJANGO_SETTINGS_MODULE', '_starter.settings')

# application = get_wsgi_application()

app_path = os.path.dirname(os.path.abspath(__file__)).replace('/config', '')
# sys.path.append(os.path.join(app_path, '_starter'))

# if os.environ.get('DJANGO_SETTINGS_MODULE') == 'config.settings.production':
#     from raven.contrib.django.raven_compat.middleware.wsgi import Sentry

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings.production")

application = get_wsgi_application()

# if os.environ.get('DJANGO_SETTINGS_MODULE') == 'config.settings.production':
#     application = Sentry(application)
