<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class WebController extends Controller
{

    public $notifications = [];

    public function __construct()
    {
        $user = User::find(7);
        $this->notifications = $user->notifications ?? [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::all();
        return view('webs.index', [
            'products' => $products,
            'notifications' => $this->notifications]);
    }

    public function contactUs(Request $request)
    {
        return view('webs.contact_us', ['notifications' => $this->notifications]);
    }

    public function readNotification(Request $request)
    {
        $id = $request->all()['id'];
        DatabaseNotification::find($id)->markAsRead();

        return response(['result' => true]);

    }

}
