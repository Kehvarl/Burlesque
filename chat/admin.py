from django.contrib import admin
from .models import Room, Post, Name
# Register your models here.


class RoomAdmin(admin.ModelAdmin):
    list_display = ["title"]


class PostAdmin(admin.ModelAdmin):
    list_display = ["raw", "name", "room_title"]

    def room_title(self, obj):
        return obj.room.title


class NameAdmin(admin.ModelAdmin):
    list_display = ["name"]


admin.site.register(Room, RoomAdmin)
admin.site.register(Post, PostAdmin)
admin.site.register(Name, NameAdmin)
