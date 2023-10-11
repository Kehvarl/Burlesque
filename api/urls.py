from django.urls import path
from .views import ChatList, ChatPosts, UserList, UserDetail

urlpatterns = [
    path('users/', UserList.as_view()),
    path('users/<int:pk>', UserDetail.as_view()),
    path('<int:pk>/', ChatPosts.as_view()),
    path('', ChatList.as_view()),
]
