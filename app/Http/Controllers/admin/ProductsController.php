<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stage;
use App\Product;
use App\ProductImages;
use Carbon\Carbon;
use File;
use Image;
class ProductsController extends Controller
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

        return view('admin.products.index', [
            'title' => trans("admin.show all products"),
            'index' =>Product::all()
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
        $stages = Stage::all();

        return view('admin.products.create', [
            'title' => trans("admin.add product"),
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
       $this->rules['stage_id'] = 'required|exists:stages,id';
       $data = $this->validate($request, $this->rules);
       //Create New Photo
       $destination = public_path().'/uploads';
       $image= $request->image;
       $data = new Product();
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
       $data->description = $request->description;
       $data->arabic_description = $request->arabic_description;
       $data->stage_id = $request->stage_id;
       $data->save();
if($request['repeater-list'] !== null){
       foreach ($request['repeater-list'] as $repeater) {

           $image= $repeater['files'];
           $imagedata = new ProductImages();
           if (File::isFile($image)) {
               $name       = $image->getClientOriginalName(); // get image name
               $extension  = $image->getClientOriginalExtension(); // get image extension
               $sha1       = sha1($name); // hash the image name
               $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
               // get the image realpath
               $uploadedImage = Image::make($image->getRealPath());
               $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
               $imagedata->image = $fileName;
               $imagedata->product_id = $data->id;
               $imagedata->save();
           }
       }
}
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('products.create');
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

        $product =Product::findOrFail($id);
        $stages = Stage::all();

        $ProductImages = ProductImages::where('product_id',$id)->get();

        return view('admin.products.edit', [
            'title' => trans("admin.edit product") . ' : ' . $product->name,
            'edit'  => $product,
            'stages' => $stages,
            'ProductImages' => $ProductImages,

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
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['arabic_description'] = 'sometimes|nullable';
        $this->rules['image'] = 'sometimes|nullable';
        $this->rules['stage_id'] = 'required|exists:stages,id';
        $data = $this->validate($request, $this->rules);
        //Create New Photo
        $destination = public_path() . '/uploads';
        $image = $request->image;
        $data =Product::findOrFail($id);
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
        $data->description = $request->description;
        $data->arabic_description = $request->arabic_description;
        $data->stage_id = $request->stage_id;
        $data->save();

        if($request['repeater-list'] !== null){

            foreach ($request['repeater-list'] as $repeater) {

                $image= $repeater['files'];
                $imagedata = new ProductImages();
                if (File::isFile($image)) {
                    $name       = $image->getClientOriginalName(); // get image name
                    $extension  = $image->getClientOriginalExtension(); // get image extension
                    $sha1       = sha1($name); // hash the image name
                    $fileName   = time(). $sha1 . "." . $extension; // create new name for the image
                    // get the image realpath
                    $uploadedImage = Image::make($image->getRealPath());
                    $uploadedImage->save( $destination . '/' . $fileName, '100'); // save the image
                    $imagedata->image = $fileName;
                    $imagedata->product_id = $data->id;
                    $imagedata->save();
                }
            }
        }

        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/products');
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
        $product =Product::findOrFail($id);
        if ($product) {
            $product->delete();
        }
    }


}
