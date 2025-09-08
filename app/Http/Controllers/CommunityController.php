<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatReply;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditTrailService;

class CommunityController extends Controller
{
    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Chat::with(['creator', 'replies.user'])
            ->orderByDesc('chat_created_at')
            ->get();

        return view('community.index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'chat_topic' => 'required|string|max:255',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $chat = new Chat();
        $chat->chat_topic = $request->chat_topic;
        $chat->chat_created_at = now();
        $chat->chat_creator_id = Auth::id();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $destination = base_path('files/images/chat_attachments');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $chat->attachment_url = $filename;
            $chat->file_type = $file->getClientMimeType();
            $chat->file_size = $file->getSize();
        }

        $chat->save();

        $this->auditTrailService->log('create_post', $chat, 'User created a new forum post');

        return redirect()->route('community.index')->with('success', 'Topic created successfully!');
    }

    /**
     * Handle a reply to a topic.
     */
    public function reply(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $reply = new ChatReply();
        $reply->chat_id = $chat->id;
        $reply->user_id = Auth::id();
        $reply->content = $request->input('content');
        $reply->created_at = now();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $destination = base_path('files/images/chat_reply_attachments');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $reply->attachment_url = $filename;
            $reply->file_type = $file->getClientMimeType();
            $reply->file_size = $file->getSize();
        }

        $reply->save();

        $this->auditTrailService->log('create_reply', $reply, 'User replied to a forum post');

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    public function destroy(Chat $community)
    {
        $this->auditTrailService->log('delete_post', $community, 'User deleted a forum post');
        $community->delete();

        return redirect()->route('community.index')->with('success', 'Post deleted successfully.');
    }
} 