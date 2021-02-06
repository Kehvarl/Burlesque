from django.urls import paths
from .views import BookAPIView

urlpatterns = [
    path('', BookAPIView.as_view()),
]
