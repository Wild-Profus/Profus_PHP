from collections import OrderedDict
from django.urls import NoReverseMatch
from rest_framework.routers import DefaultRouter, APIRootView
from rest_framework.response import Response
from rest_framework.reverse import reverse


class VersionView(APIRootView):
    my_routes = {
        'dofus2': 'dofus2',
        'retro': 'retro',
        'touch': 'touch'
    }

    def get(self, request, *args, **kwargs):
        ret = OrderedDict()
        namespace = request.resolver_match.namespace
        for key, url_name in self.my_routes.items():
            if namespace:
                url_name = namespace + ':' + url_name
            try:
                ret[key] = reverse(
                    f'{url_name}:api-root',
                    args=args,
                    kwargs=kwargs,
                    request=request,
                    format=kwargs.get('format', None)
                )
            except NoReverseMatch:
                continue
        return Response(ret)

class VersionRouter(DefaultRouter):
    APIRootView = VersionView
