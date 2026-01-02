<?php

namespace App\Http\Controllers\Activities;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\CommentStoreRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(CommentStoreRequest $request)
    {
        $validated = $request->validated();

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
