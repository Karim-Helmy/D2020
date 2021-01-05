<?php

namespace App\Http\Controllers\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscriber;

class HomeController extends Controller
{

    /**
     * [Trainer Home]
     * @return [type] [description]
     */
    public function index()
    {
        $subscriber = Subscriber::where('id',auth()->user()->subscriber_id)->first();
        $photos = explode('|', $subscriber->photos);
        return view('trainer.index', [
            'index' => $subscriber,
            'photos' => $photos
        ]);
    }




}
