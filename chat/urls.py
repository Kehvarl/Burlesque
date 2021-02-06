from django.urls import paths

from .views import ChatListView

urlpatterns = [
    path('', ChatListView.as_view(), name='home'),
]
