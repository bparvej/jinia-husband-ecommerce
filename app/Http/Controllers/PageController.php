<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class PageController extends Controller
{
    private function getCategories()
    {
        return Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }

    public function about()
    {
        return view('pages.about', [
            'title' => 'About Us — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'title' => 'Contact Us — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }

    public function ourStory()
    {
        return view('pages.our-story', [
            'title' => 'Our Story — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }

    public function returns()
    {
        return view('pages.returns', [
            'title' => 'Returns & Exchanges — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }

    public function shipping()
    {
        return view('pages.shipping', [
            'title' => 'Shipping Policy — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }

    public function faqs()
    {
        return view('pages.faqs', [
            'title' => 'FAQs — HomeI Cozy Living',
            'categories' => $this->getCategories()
        ]);
    }
}
