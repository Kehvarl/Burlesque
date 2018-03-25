<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\User;
use App\ChatPost;

class ChatPostsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chats/index')->with('rooms', Room::orderBy('name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $room = Room::find($request->get('room'));
      $chat_post = new ChatPost();
      $chat_post->user()->associate(auth()->user());
      $chat_post->room()->associate($room);
      $chat_post->display_name = auth()->user()->name;
      $chat_post->message_font = $request->get('message_font');
      $chat_post->message_color = $request->get('message_color');
      $chat_post->raw_message = $request->get('raw');
      $chat_post->message = $request->get('raw');
      $chat_post->save();

      return redirect("/chats/$room->id");
    }

    /**
     * Enter a room
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function login(Room $room)
    {
      $room->users()->syncWithoutDetaching([auth()->user()->id]);
      return redirect("/chats/$room->id");
    }

    /**
     * Leave the room.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function logout(Room $room)
    {
      $room->users()->detach([auth()->user()->id]);
      return redirect("/chats");
    }

    /**
     * Display the specified resource.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return view('chats/show')->with('room', $room);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
