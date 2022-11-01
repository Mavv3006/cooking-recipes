<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws AuthorizationException|ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Comment::class);

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:250',
            'recipe_id' => 'required|numeric|min:1',
        ]);

        $data = $validator->validate();

        $comment_data = [
            'comment' => $data['comment'],
            'recipe_id' => $data['recipe_id'],
            'user_id' => Auth::id()
        ];

        Log::debug(json_encode($comment_data));
        $comment = Comment::create($comment_data);
        Log::info('Created comment ' . $comment->id);

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Comment  $comment
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $data = $request->validate([
            'comment' => 'required|string|max:250'
        ]);

        Log::info('Updating comment ' . $comment->id);
        $comment->update($data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Comment  $comment
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $recipe_id = $comment->recipe_id;
        Log::info('Deleting comment ' . $comment->id);
        $comment->delete();

        return redirect()->route('recipes.show', ['recipe' => $recipe_id]);
    }
}
