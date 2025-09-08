<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatReplyResource;
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
        
        $baseUrl = config('app.url');

        $data = $posts->map(function ($chat) use ($baseUrl) {
            $photoUrl = $chat->creator->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $chat->creator->photo]) : null;
            $attachmentUrl = $chat->attachment_url ? route('images.show', ['folder' => 'chat_attachments', 'filename' => $chat->attachment_url]) : null;
            return [
                'id' => $chat->id,
                'title' => $chat->chat_topic,
                'content' => $chat->content,
                'creator' => [
                    'id' => $chat->creator->id ?? null,
                    'name' => $chat->creator->name ?? trim(($chat->creator->first_name ?? '') . ' ' . ($chat->creator->last_name ?? '')),
                    'photo_url' => $photoUrl,
                ],
                'created_at' => $chat->chat_created_at->toIso8601String(),
                'attachment_url' => $attachmentUrl,
                'replies' => $chat->replies->map(function ($reply) use ($baseUrl) {
                    return [
                        'id' => $reply->id,
                        'content' => $reply->content,
                        'user' => [
                            'id' => $reply->user->id ?? null,
                            'name' => $reply->user->name ?? trim(($reply->user->first_name ?? '') . ' ' . ($reply->user->last_name ?? '')),
                            'photo_url' => $reply->user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $reply->user->photo]) : null,
                        ],
                        'created_at' => $reply->created_at->toIso8601String(),
                        'attachment_url' => $reply->attachment_url ? route('images.show', ['folder' => 'chat_reply_attachments', 'filename' => $reply->attachment_url]) : null,
                    ];
                })->values(),
            ];
        });

        return response()->json(['data' => $data->values()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'chat_topic' => 'required|string|max:255',
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            DB::beginTransaction();

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
            }
            
            $chat->save();

            // Log post creation
            $this->auditTrailService->log('create_post', $chat, 'User created a new forum post (API)');

            DB::commit();

            $chat->load(['creator']);
            
            $baseUrl = config('app.url');
            $photoUrl = $chat->creator->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $chat->creator->photo]) : null;
            $attachmentUrl = $chat->attachment_url ? route('images.show', ['folder' => 'chat_attachments', 'filename' => $chat->attachment_url]) : null;

            return response()->json([
                'data' => [
                    'id' => $chat->id,
                    'title' => $chat->chat_topic,
                    'creator' => [
                        'id' => $chat->creator->id ?? null,
                        'name' => $chat->creator->name ?? trim(($chat->creator->first_name ?? '') . ' ' . ($chat->creator->last_name ?? '')),
                        'photo_url' => $photoUrl,
                    ],
                    'created_at' => $chat->chat_created_at->toIso8601String(),
                    'attachment_url' => $attachmentUrl,
                    'replies' => [],
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create topic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $chat = Chat::with(['creator', 'replies.user'])->findOrFail($id);
            $baseUrl = config('app.url');

            $topic = [
                'id' => $chat->id,
                'title' => $chat->chat_topic,
                'creator' => [
                    'id' => $chat->creator->id ?? null,
                    'name' => $chat->creator->name ?? trim(($chat->creator->first_name ?? '') . ' ' . ($chat->creator->last_name ?? '')),
                    'photo_url' => $chat->creator->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $chat->creator->photo]) : null,
                ],
                'chat_created_at' => $chat->chat_created_at ? $chat->chat_created_at->toIso8601String() : null,
                'messages' => [
                    [
                        'id' => $chat->id,
                        'content' => $chat->content,
                        'author' => [
                            'id' => $chat->creator->id ?? null,
                            'name' => $chat->creator->name ?? trim(($chat->creator->first_name ?? '') . ' ' . ($chat->creator->last_name ?? '')),
                            'photo_url' => $chat->creator->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $chat->creator->photo]) : null,
                        ],
                        'created_at' => $chat->chat_created_at ? $chat->chat_created_at->toIso8601String() : null,
                        'replies' => $chat->replies->map(function ($reply) use ($baseUrl) {
                            return [
                                'id' => $reply->id,
                                'user' => [
                                    'id' => $reply->user->id ?? null,
                                    'name' => $reply->user->name ?? trim(($reply->user->first_name ?? '') . ' ' . ($reply->user->last_name ?? '')),
                                    'photo_url' => $reply->user->photo ? route('images.show', ['folder' => 'profile-photos', 'filename' => $reply->user->photo]) : null,
                                ],
                                'content' => $reply->content,
                                'created_at' => $reply->created_at ? $reply->created_at->toIso8601String() : null,
                            ];
                        })->values(),
                    ]
                ],
            ];

            return response()->json(['status' => 'success', 'data' => $topic]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch topic',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $chat = Chat::findOrFail($id);

            // Only allow creator to update the topic
            if ($chat->chat_creator_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to update this topic'
                ], 403);
            }

            $request->validate([
                'chat_topic' => 'required|string|max:255',
            ]);

            $chat->chat_topic = $request->chat_topic;
            $chat->save();

            return response()->json([
                'status' => 'success',
                'data' => $chat->load(['creator']),
                'message' => 'Topic updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update topic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $chat = Chat::findOrFail($id);

            // Only allow creator to delete the topic
            if ($chat->chat_creator_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete this topic'
                ], 403);
            }

            $chat->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Topic deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete topic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reply(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        // Custom validation: at least one of content or attachment must be present
        if (empty($request->input ( 'content')) && !$request->hasFile('attachment')) {
            return response()->json([
                'message' => 'Either content or attachment is required.',
                'errors' => [
                    'content' => ['Either content or attachment is required.']
                ]
            ], 422);
        }

        $reply = new ChatReply();
        $reply->chat_id = $chat->id;
        $reply->user_id = Auth::id();
        $reply->content = $request->input ( 'content');

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $destination = base_path('files/images/chat_reply_attachments');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $reply->attachment_url = $filename;
        }

        $reply->save();

        // Log reply creation
        $this->auditTrailService->log('create_reply', $reply, 'User replied to a forum post (API)');

        // Load the user relationship so it can be returned in the response
        $reply->load('user');

        // Return a JSON response with a 201 "Created" status code
        return response()->json([
            'message' => 'Reply posted successfully!',
            'data' => new ChatReplyResource($reply)
        ], 201);
    }
}
