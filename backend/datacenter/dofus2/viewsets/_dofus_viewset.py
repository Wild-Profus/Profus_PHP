from rest_framework import viewsets
from rest_framework.decorators import action
from rest_framework.response import Response
from rest_framework.pagination import LimitOffsetPagination
from rest_framework.permissions import IsAuthenticated, IsAdminUser, AllowAny


class DofusPagination(LimitOffsetPagination):
    default_limit = None
    limit_query_param = 'limit'
    offset_query_param = 'offset'
    max_limit = None
    template = "rest_framework/pagination/numbers.html"

class DofusViewSet(viewsets.ModelViewSet):
    model_class = None
    default_serializer = None
    local_serializer = None
    pagination_class = DofusPagination

    # def __init__(self, *args, model, **kwargs):
    #     super().__init__(*args, **kwargs)
    #     self.model_class = model
    #     self.default_serializer = model.default_serializer
    #     self.local_serializer = model.local_serializer

    @action(detail=False, methods=['get'])
    def all(self, request, *args, **kwargs):
        queryset = self.filter_queryset(self.get_queryset())
        page = self.paginate_queryset(queryset)
        if page is not None:
            serializer = self.get_serializer(page, many=True)
            response = self.get_paginated_response(serializer.data)
            response.data['results'] = {row['id']: row for row in response.data['results']}
            return response
        serializer = self.get_serializer(queryset, many=True)
        response = Response(serializer.data)
        response.data = {row['id']: row for row in response.data}
        return response

    def get_serializer(self, *args, **kwargs):
        serializer_class = self.get_serializer_class()
        kwargs['context'] = self.get_serializer_context()
        if isinstance(kwargs.get('data', {}), list):
            kwargs['many'] = True
        return serializer_class(*args, **kwargs)

    def get_serializer_class(self):
        if self.action in ['list', 'all']:
            return self.local_serializer
        return self.default_serializer

    def get_permissions(self):
        if self.action in ['create', 'update', 'partial_update']:
            permission_classes = [IsAuthenticated] # [IsTrusted]
        elif self.action == 'destroy':
            permission_classes = [IsAdminUser]
        else:
            permission_classes = [AllowAny]
        return [permission() for permission in permission_classes]

    def get_queryset(self):
        if self.action in ['create', 'update', 'partial_update', 'retrieve']:
            queryset = self.model_class.objects.all()
        else:
            lang = self.request.LANGUAGE_CODE
            queryset = self.model_class.objects.local_only(lang)
        return queryset
