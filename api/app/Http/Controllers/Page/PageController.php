<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with(['user'])->withCount(['likes'])->paginate();

        return PageResource::collection($pages);
    }

    public function store(SavePageRequest $request)
    {
        $page = auth()->user()->pages()->create($request->only(['name', 'description']));

        return new PageResource($page);
    }

    public function show(Page $page)
    {
        $page->load('user');
        $page->loadCount(['likes']);

        return new PageResource($page);
    }

    public function update(SavePageRequest $request, Page $page)
    {
        $this->authorize('update', $page);

        $page->update($request->only(['name', 'description']));

        return new PageResource($page);
    }

    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);

        $page->delete();

        return response()->noContent();
    }
}
