<?php

namespace App\Http\Controllers\Activities;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'content' => 'required|string|min:3|max:1000'
        ]);

        Comment::create([
            'user_id' => auth()->user()->id,
            'activity_id' => $validated['activity_id'],
            'content' => $validated['content'],
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
    }
}
