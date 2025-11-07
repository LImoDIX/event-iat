<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * Class NewsController
 *
 * @property string|null $photo_url
 */
class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        
        $news->getCollection()->transform(function ($item) {
            if ($item->photo_path) {
                $item->photo_url = asset('storage/' . $item->photo_path);
            }
            return $item;
        });
        
        return inertia('Admin/News/Index', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for creating a new news item.
     */
    public function create()
    {
        return inertia('Admin/News/Create');
    }

    /**
     * Store a newly created news item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2MB
            'is_active' => 'boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('news-photos', 'public');
        }

        $news = News::create([
            'title' => $request->title,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'is_active' => $request->is_active ?? true,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно создана.');
    }

    /**
     * Show the form for editing the specified news item.
     */
    public function edit(News $news)
    {
        return inertia('Admin/News/Edit', [
            'news' => $news,
        ]);
    }

    /**
     * Update the specified news item in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2MB
            'is_active' => 'boolean',
        ]);

        // Обработка загрузки фотографии
        $photoPath = $news->photo_path; // Сохраняем текущий путь к фото
        if ($request->hasFile('photo')) {
            // Удаляем старое фото, если оно было
            if ($news->photo_path) {
                Storage::disk('public')->delete($news->photo_path);
            }
            // Загружаем новое фото
            $photoPath = $request->file('photo')->store('news-photos', 'public');
        }

        $news->update([
            'title' => $request->title,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно обновлена.');
    }

    /**
     * Remove the specified news item from storage.
     */
    public function destroy(News $news)
    {
        // Удаляем фото, если оно было
        if ($news->photo_path) {
            Storage::disk('public')->delete($news->photo_path);
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно удалена.');
    }

    /**
     * Get active news for display on dashboards.
     */
    public function getActiveNews()
    {
        return News::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($news) {
                if ($news->photo_path) {
                    $news->photo_url = asset('storage/' . $news->photo_path);
                }
                return $news;
            });
    }
    
    /**
     * Get a random active news item for banner display.
     */
    public function getRandomActiveNews()
    {
        $news = News::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        if ($news && $news->photo_path) {
            $news->photo_url = asset('storage/' . $news->photo_path);
        }
        
        return $news;
    }
    
    /**
     * Display the specified news item.
     */
    public function show(News $news)
    {
        // Проверяем, что новость активна
        if (!$news->is_active) {
            abort(404);
        }
        
        if ($news->photo_path) {
            $news->photo_url = asset('storage/' . $news->photo_path);
        }
        
        // Загружаем информацию об авторе
        $news->load('user');
        
        return inertia('News/Show', [
            'news' => $news,
        ]);
    }
}