from rest_framework.routers import DefaultRouter, APIRootView


class Dofus2View(APIRootView):
    pass

class Dofus2Router(DefaultRouter):
    APIRootView = Dofus2View
