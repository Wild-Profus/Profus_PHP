from django.urls import path, include
from . import routers
from . import viewsets

app_name = 'datacenter.dofus2'

router = routers.Dofus2Router()
# ordered for display in api url, in alphabetical order
router.register(r'characteristics', viewsets.CharacteristicViewSet, 'characteristic')
router.register(r'emotes', viewsets.EmoteViewSet, 'emote')
router.register(r'ornaments', viewsets.OrnamentViewSet, 'ornament')
router.register(r'spells', viewsets.SpellViewSet, 'spell')
router.register(r'itemprices', viewsets.ItemPriceViewSet, 'itemprice')
# router.register(r'spellstates', viewsets.SpellStateViewSet, 'spellstate')
router.register(r'servers', viewsets.ServerViewSet, 'server')
# router.register(r'titles', viewsets.TitleViewSet, 'title')

urlpatterns = [
    path('', include(router.urls)),
]
