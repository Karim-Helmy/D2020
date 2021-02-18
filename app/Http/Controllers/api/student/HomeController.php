<?php
namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Category;
use App\City;
use App\Subscriber;
use App\Stage;
use App\StageImages;
use App\StageReview;


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

    public function category($id)
    {
    $data = Stage::where('category_id',$id)->get();
    return sendResponse(trans('admin.stages'),$data);
    }
    public function store_details($id)
    {
        $data = Stage::where('id',$id)->get();
        $stageImages = StageImages::where('stage_id',$id)->get();
        $StageReviews = StageReview::where('stage_id',$id)->get();

        return sendResponse(trans('admin.stages'),['data'=>$data,'images'=>$stageImages,'reviews'=> $StageReviews]);
    }

    public function add_store_review($id,Request $request)
    {
        $validator = Validator::make(request()->all(), [
            "rating"     => 'numeric',
            "review"      => 'sometimes|nullable'
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }
        $data = new StageReview();
        $data->rating = $request->rating;
        $data->review = $request->review;
        //$data->user_id = auth()->id();
        $data->stage_id = $id;
        $data->save();

        return sendResponse(trans('admin.add Successfully'),$data);

    }
    public function add_store_location($id,Request $request)
    {
        $validator = Validator::make(request()->all(), [
            "long"     => 'required',
            "lat"      => 'required'
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }
        $data = Stage::where('id',$id)->get();
        $data->longi = $request->long;
        $data->lati = $request->lat;
        //$data->user_id = auth()->id();
        $data->save();

        return sendResponse(trans('admin.add Successfully'),$data);

    }
    }
