<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $favorites = Auth::user()
            ->favorites()
            ->with(['user' => fn($query) => $query->select('id', 'name')])
            ->orderByPivot('created_at', 'desc')
            ->select('id', 'title', 'description', 'difficulty', 'recipes.user_id')
            ->get();
        return Inertia::render('Favorites/Index', ['favorites' => $favorites]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Recipe $recipe
     * @return RedirectResponse
     */
    public function store(Recipe $recipe): RedirectResponse
    {
        $favorite = Auth::user()->favorites()->where('id', $recipe->id)->first();

        if ($favorite) {
            Auth::user()->favorites()->detach($recipe->id);
        } else {
            Auth::user()->favorites()->attach($recipe->id);
        }

        return back();
    }
}
