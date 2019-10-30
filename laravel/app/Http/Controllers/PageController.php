<?php

namespace App\Http\Controllers;

use Backpack\PageManager\app\Models\Page;

class PageController extends Controller
{
    private $data = [];

    public function index($slug, $subs = null)
    {
        $page = Page::findBySlug($slug);

        if (!$page)
        {
            abort(404, trans('back-to-home'));
        }

        $this->data['title'] = $page->title;
        $this->data['page'] = $page->withFakes();

        return view('pages.'.$page->template, $this->data);
    }
}
