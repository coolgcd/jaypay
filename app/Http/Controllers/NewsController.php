<?php
namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::orderBy('id', 'desc')->get();

        return view('admin.sitemanagment.managenews', compact('news'));
    }

    public function create()
    {
        return view('admin.sitemanagment.add_news');
    }

    public function store(Request $request)
    {
        $request->validate([
            'news' => 'required|string|max:255',
        ]);

        News::create($request->all());

        return redirect()->route('news.index')->with('message', 'News added successfully.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.sitemanagment.edit_news', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'news' => 'required|string|max:255',
        ]);

        $news = News::findOrFail($id);
        $news->update($request->all());

        return redirect()->route('news.index')->with('message', 'News updated successfully.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('message', 'News successfully deleted.');
    }
}
