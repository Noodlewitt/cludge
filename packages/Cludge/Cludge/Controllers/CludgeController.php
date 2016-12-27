<?php

namespace Cludge\Controllers;

use Cludge\Facades\Actions;
use Cludge\Models\Content;
use Cludge\Models\Slug;
use Illuminate\Http\Request;

class CludgeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Load a page.
     */
    public function page($slug = '/'){

        $slug = Slug::where('full_slug', $slug)->first();

        $item = $slug->slugged()->first(); //this is normally a content item. but might not be.

        return Actions::runSlugAction($slug, $item, []);
    }
}
