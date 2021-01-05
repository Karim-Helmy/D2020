<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Pioneer;
use App\Category;



class FrontAuthController extends Controller
{
    public function sessionStore(Request $request)
    {
        $data = $this->validate($request, [
        'username'=>'required',
        'password'=>'required',
        ]);
        //IF Check On Remember Me
        if($request->remember == "on"){
            $remember = true;
        }else{
            $remember = false;
        }
        if(!auth()->attempt($data,$remember))
        {
            return redirect()->back()->with([
                'error' => trans('admin.login fail'),
            ]);
        }

        //\Cookie::get('besteam')/545323259
        return redirect('/checklogin');
    }


    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout()
    {

           auth()->logout();
           return redirect('/');


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function project()
    {
        $pioneer = Pioneer::with('category')->limit(100)->get();
        $categories = Category::limit(100)->whereHas('pioneer')->get();
        if (auth()->user()->type == '1') {
            return view('super.pioneer', [
                'pioneers' => $pioneer,
                'categories' => $categories,
            ]);
        }elseif (auth()->user()->type == '2') {
            return view('trainer.pioneer', [
                'pioneers' => $pioneer,
                'categories' => $categories,
            ]);
        }elseif (auth()->user()->type == '3') {
            return view('student.pioneer', [
                'pioneers' => $pioneer,
                'categories' => $categories,
            ]);
        }
    }
}
