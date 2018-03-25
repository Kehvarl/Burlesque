<div style='font-family:"{{ $chat_post->message_font}}" serif; color: {{ $chat_post->message_color}}'>
  <span class="username">{{ $chat_post->display_name }}: </span>
  <span class="message">{{ $chat_post->message }}</span>
  <span class="timestamp small"> - {{ $chat_post->created_at->diffForHumans() }}</span>
</div>
