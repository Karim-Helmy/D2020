<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Stage;
use App\Product;

use File;
use Image;

class CategoriesController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('categories')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        return view('admin.categories.index', [
            'title' => trans("admin.show all categories"),
            'index' => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('categories')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        return view('admin.categories.create', [
            'title' => trans("admin.add category"),
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
       if(!userCan('categories')){
          return redirect('admin/index')->with([
          'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
          ]);
      }
       $this->rules['name'] = 'required|unique:categories,name';
       $this->rules['image'] = 'required|image';

       $data = $this->validate($request, $this->rules);
       //Create New Photo
       $destination = public_path().'/uploads';
       $image= $request->image;

       $data = new Category();

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
       $data->save();
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('categories.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('categories')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

        $category = Category::findOrFail($id);
        return view('admin.categories.edit', [
            'title' => trans("admin.edit category") . ' : ' . $category->name,
            'edit'  => $category,
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
        if(!userCan('categories')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        $this->rules['name'] = 'required|unique:caregories,name,'.$id;


        $data = $this->validate($request, $this->rules);
        //Create New Photo
        $destination = public_path().'/uploads';
        $image= $request->image;

        $data = Category::findOrFail($id);

        if (File::isFile($image)) {
            @unlink(public_path() . '/uploads/' . $data->image);
            $name       = $image->getClientOriginalName(); // get image name
            $extension  = $image->getClientOriginalExtension(); // get image extension
            $sha1       = sha1($name); // hash the image name
            $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
            // get the image realpath
            $uploadedImage = Image::make($image->getRealPath());
            $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
            if ($image->move($destination, $fileName)) {
                $data->image = $fileName;
            }

        }
        //Update Data
        $data->name = $request->name;
        $data->save();
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/categories');
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
        if(!userCan('categories')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $category = Category::findOrFail($id);
        $data = Stage::where('category_id',$id)->get();
        foreach ($data as $stage){
            @unlink(public_path() . '/uploads/' . $stage->image);
            $stage->delete();
        }

        if ($category) {
            @unlink(public_path() . '/uploads/' . $category->image);

            $category->delete();
        }
    }


}
