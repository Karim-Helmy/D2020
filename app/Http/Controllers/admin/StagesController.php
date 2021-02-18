<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stage;
use App\StageImages;
use App\Category;
use Carbon\Carbon;
use File;
use Image;
class StagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        return view('admin.stages.index', [
            'title' => trans("admin.show all stages"),
            'index' => Stage::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $subscribers = Subscriber::all();
        $categories = Category::all();
        $cities = City::all();

        return view('admin.stages.create', [
            'title' => trans("admin.add stage"),
            'categories' => $categories,
            'subscribers' => $subscribers,
            'cities' => $cities,

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
       if(!userCan('subscribers')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       $this->rules['name'] = 'required';
       $this->rules['arabic_name'] = 'required';
       $this->rules['address'] = 'required';
       $this->rules['description'] = 'sometimes|nullable';
       $this->rules['arabic_description'] = 'sometimes|nullable';
       $this->rules['image'] = 'required|image';
       $this->rules['category_id'] = 'required|exists:categories,id';
       $this->rules['city_id'] = 'required|exists:cities,id';
       $this->rules['subscriber_id'] = 'required|exists:subscribers,id';
       $this->rules['map'] = 'sometimes|nullable';
       $this->rules['end'] = 'required|date';
       $data = $this->validate($request, $this->rules);
       //Create New Photo
       $destination = public_path().'/uploads';
       $image= $request->image;
       $data = new Stage();
       if (File::isFile($image)) {
           $name       = $image->getClientOriginalName(); // get image name
           $extension  = $image->getClientOriginalExtension(); // get image extension
           $sha1       = sha1($name); // hash the image name
           $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
           // get the image realpath
           $uploadedImage = Image::make($image->getRealPath());
           $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
           $data->image = $fileName;

       }
       $data->name = $request->name;
       $data->arabic_name = $request->arabic_name;
       $data->address = $request->address;
       $data->description = $request->description;
       $data->arabic_description = $request->arabic_description;
       $data->category_id = $request->category_id;
       $data->city_id = $request->city_id;
       $data->subscriber_id = $request->subscriber_id;
       $data->map = $request->map;
       $data->end = $request->end;
       $data->begin = Carbon::createFromFormat('Y-m-d', $request->end)->subYears(1);
       $data->save();
if($request['repeater-list'] !== null){
       foreach ($request['repeater-list'] as $repeater) {

           $image= $repeater['files'];
           $imagedata = new StageImages();
           if (File::isFile($image)) {
               $name       = $image->getClientOriginalName(); // get image name
               $extension  = $image->getClientOriginalExtension(); // get image extension
               $sha1       = sha1($name); // hash the image name
               $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
               // get the image realpath
               $uploadedImage = Image::make($image->getRealPath());
               $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
               $imagedata->image = $fileName;
               $imagedata->stage_id = $data->id;
               $imagedata->save();
           }
       }
}
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('stages.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $stage = Stage::findOrFail($id);
        $subscribers = Subscriber::all();
        $categories = Category::all();
        $cities = City::all();
        $stageImages = StageImages::where('stage_id',$id)->get();

        return view('admin.stages.edit', [
            'title' => trans("admin.edit stage") . ' : ' . $stage->name,
            'edit'  => $stage,
            'categories' => $categories,
            'subscribers' => $subscribers,
            'cities' => $cities,
            'stageImages' => $stageImages,

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
        if (!userCan('subscribers')) {
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $this->rules['name'] = 'required';
        $this->rules['arabic_name'] = 'required';
        $this->rules['address'] = 'required';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['arabic_description'] = 'sometimes|nullable';
        $this->rules['image'] = 'sometimes|nullable';
        $this->rules['category_id'] = 'required|exists:categories,id';
        $this->rules['city_id'] = 'required|exists:cities,id';
        $this->rules['subscriber_id'] = 'required|exists:subscribers,id';
        $this->rules['map'] = 'sometimes|nullable';
        $this->rules['end'] = 'sometimes|nullable|date';
        $data = $this->validate($request, $this->rules);
        //Create New Photo
        $destination = public_path() . '/uploads';
        $image = $request->image;
        $data = Stage::findOrFail($id);
        if (File::isFile($image)) {
            $name = $image->getClientOriginalName(); // get image name
            $extension = $image->getClientOriginalExtension(); // get image extension
            $sha1 = sha1($name); // hash the image name
            $fileName = time() . $sha1 . "." . $extension; // create new name for the image
            // get the image realpath
            $uploadedImage = Image::make($image->getRealPath());
            $uploadedImage->save($destination . '/' . $fileName, '100'); // save the image
            $data->image = $fileName;

        }
        $data->name = $request->name;
        $data->arabic_name = $request->arabic_name;
        $data->address = $request->address;
        $data->description = $request->description;
        $data->arabic_description = $request->arabic_description;
        $data->category_id = $request->category_id;
        $data->city_id = $request->city_id;
        $data->subscriber_id = $request->subscriber_id;
        $data->map = $request->map;
        if ($request->end !== null)
        {
            $data->end = $request->end;
            $data->begin = Carbon::createFromFormat('Y-m-d', $request->end)->subYears(1);
        }
        $data->save();

        if($request['repeater-list'] !== null){

            foreach ($request['repeater-list'] as $repeater) {

                $image= $repeater['files'];
                $imagedata = new StageImages();
                if (File::isFile($image)) {
                    $name       = $image->getClientOriginalName(); // get image name
                    $extension  = $image->getClientOriginalExtension(); // get image extension
                    $sha1       = sha1($name); // hash the image name
                    $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
                    // get the image realpath
                    $uploadedImage = Image::make($image->getRealPath());
                    $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
                    $imagedata->image = $fileName;
                    $imagedata->stage_id = $data->id;
                    $imagedata->save();
                }
            }
        }

        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/stages');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  bool  $redirect
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $stage = Stage::findOrFail($id);
        $stageImages = StageImages::where('stage_id',$id)->get();
        foreach ($stageImages as $stage){
            @unlink(public_path() . '/uploads/' . $stage->image);
            $stage->delete();
        }
        if ($stage) {
            @unlink(public_path() . '/uploads/' . $stage->image);
            $stage->delete();
        }
    }


}
