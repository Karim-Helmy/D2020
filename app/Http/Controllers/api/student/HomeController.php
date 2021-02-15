<?php
namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Category;
use App\City;
use App\Subscriber;
use App\Stage;


class HomeController extends Controller
{

    /**
    * [exam index]
    * @param  [int] $course_id
    * route(api.student.exam)
    * api_url: api/student/exams/{course_id}  [method:get]
    * @return [json]
    */
    public function categories()
    {
        $data = Category::all();
        return sendResponse(trans('admin.categories'),$data);
        }

    public function cities()
    {
        $data = City::all();
        return sendResponse(trans('admin.cities'),$data);
    }
    public function subscribers()
    {
        $data = Subscriber::all();
        return sendResponse(trans('admin.subscribers'),$data);


    }   public function stores()
    {
        $data = Stage::all();
        return sendResponse(trans('admin.stages'),$data);
    }

    }
