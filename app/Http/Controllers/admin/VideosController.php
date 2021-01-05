<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Video;
use App\Category;
use App\Stage;
use App\Rules\ValidateYoutube;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('media')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $categories = Category::all();
        $stages = Stage::all();
        $video = Video::where('admin_id','!=','0')->with('category','stage');
        if(request()->filled('keyword')){
            $video->where('title','like','%'.request()->keyword.'%');
        }
        if(request()->filled('category')){
            $video->where('category_id',request()->category);
        }
        if(request()->filled('stage')){
            $video->where('stage_id',request()->stage);
        }
        $videos = $video->paginate(12);
        return view('admin.videos.index', [
            'title' => trans("admin.show all videos"),
            'index' => $videos,
            'categories' => $categories,
            'stages' => $stages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('media')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $categories = Category::all();
        $stages = Stage::all();
        return view('admin.videos.create', [
            'title' => trans("admin.add videos"),
            'categories' => $categories,
            'stages' => $stages
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       if(!userCan('media')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       // Make Validation
       $this->rules['title'] = 'required|max:200';
       $this->rules['description'] = 'sometimes|nullable';
       $this->rules['link'] = 'required|url';
       $this->rules['link'] = [new ValidateYoutube];
       $this->rules['category_id'] = 'required|exists:categories,id';
       $this->rules['stage_id'] = 'required|exists:stages,id';
       $data = $this->validate($request, $this->rules);
       $data['admin_id'] = auth()->guard('webAdmin')->id();
       Video::create($data);
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('videos.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('media')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $videos = Video::where('admin_id','!=','0')->where('id',$id)->firstOrFail();
        $stages = Stage::all();
        $categories = Category::all();
        return view('admin.videos.edit', [
            'title' => trans("admin.edit videos") . ' : ' . $videos->title,
            'edit'  => $videos,
            'categories' => $categories,
            'stages' => $stages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!userCan('media')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['link'] = 'required|url';
        $this->rules['link'] = [new ValidateYoutube];
        $this->rules['category_id'] = 'required|exists:categories,id';
        $this->rules['stage_id'] = 'required|exists:stages,id';
        $data = $this->validate($request, $this->rules);
        //Update Data
        Video::where('admin_id','!=','0')->where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/videos');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  bool  $redirect
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         if(!userCan('media')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $video = Video::where('admin_id','!=','0')->where('id',$id)->firstOrFail();
         $video->delete();
         return  redirect()->back();
     }


}
