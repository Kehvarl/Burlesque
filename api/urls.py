from django.urls import paths
from .views import ChatAPIView

urlpatterns = [
    path('', ChatAPIView.as_view()),
]
