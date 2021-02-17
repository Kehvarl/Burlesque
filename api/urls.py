from django.urls import path
from .views import ChatList, ChatPosts

urlpatterns = [
    path('<int:pk>/', ChatPosts.as_view()),
    path('', ChatList.as_view()),
]
