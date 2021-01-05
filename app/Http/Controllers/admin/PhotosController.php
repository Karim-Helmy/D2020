<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Photo;
use App\Category;
use App\Stage;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!userCan('media')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $categories = Category::all();
        $stages = Stage::all();
        $photo = Photo::with('category','stage');
        if(request()->filled('keyword')){
            $photo->where('title','like','%'.request()->keyword.'%');
        }
        if(request()->filled('category')){
            $photo->where('category_id',request()->category);
        }
        if(request()->filled('stage')){
            $photo->where('stage_id',request()->stage);
        }
        $photos = $photo->where('admin_id','!=','0')->paginate(12);
        return view('admin.photos.index', [
            'title' => trans("admin.show all photos"),
            'index' => $photos,
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
        return view('admin.photos.create', [
            'title' => trans("admin.add photos"),
            'categories' => $categories,
            'stages' => $stages,
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
       $this->rules['image'] = 'required|image';
       $this->rules['category_id'] = 'required|exists:categories,id';
       $this->rules['stage_id'] = 'required|exists:stages,id';
       $data = $this->validate($request, $this->rules);
       //Create New Photo
       $data['admin_id'] = auth()->guard('webAdmin')->id(); // Admin ID
       $destination = "uploads/images/" . date("Y") . "/" . date("m") . "/";
       $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
       Photo::create($data);
       //Success Message
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('photos.create');
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

        $photos = Photo::findOrFail($id);
        $categories = Category::all();
        $stages = Stage::all();
        return view('admin.photos.edit', [
            'title' => trans("admin.edit photos") . ' : ' . $photos->title,
            'edit'  => $photos,
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

        $photo = Photo::findOrFail($id);
        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['image'] = 'sometimes|nullable|image';
        $this->rules['category_id'] = 'required|exists:categories,id';
        $this->rules['stage_id'] = 'required|exists:stages,id';
        $data = $this->validate($request, $this->rules);
        //Update Data
        $data['admin_id'] = auth()->guard('webAdmin')->id();
        if ($request->hasFile('image')) {
           if (file_exists(public_path('uploads/' . $photo->image))) {
               @unlink(public_path('uploads/' . $photo->image));
           }
           $destination = "uploads/images/" . date("Y") . "/" . date("m") . "/";
           $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
       }
        $photo->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/photos');
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
        
        $photos = Photo::findOrFail($id);
        if ($photos) {
            if (file_exists(public_path('uploads/' . $photos->image))) {
                @unlink(public_path('uploads/' . $photos->image));
            }
            $photos->delete();
        }
        return  redirect()->back();
    }


}
