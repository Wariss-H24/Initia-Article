<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    return response()->json(Article::all());
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('articles', 'public');
    }

    $article = Article::create([
        'title' => $request->title,
        'content' => $request->content,
        'image' => $path,
    ]);

    return response()->json($article, 201);
}


    /**
     * Display the specified resource.
     */
    // Afficher un article
    public function show(Article $article)
    {
        return response()->json($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Article $article)
{
    $request->validate([
        'title'   => 'sometimes|required|string|max:255',
        'content' => 'sometimes|required|string',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Mise à jour du titre
    if ($request->filled('title')) {
        $article->title = $request->input('title');
    }

    // Mise à jour du contenu
    if ($request->filled('content')) {
        $article->content = $request->input('content');
    }

    // Mise à jour de l'image
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('articles', 'public');
        $article->image = $path;
    }

    $article->save();

    return response()->json($article);
}



    /**
     * Remove the specified resource from storage.
     */
public function destroy(Article $article)
{
    $article->delete();
    return response()->json(null, 204);
}

}
