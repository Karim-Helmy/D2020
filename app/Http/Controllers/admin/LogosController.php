<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;

class LogosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('partners')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        return view('admin.logos.index', [
            'title' => trans("admin.show all logos"),
            'index' => Logo::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('partners')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        return view('admin.logos.create', [
            'title' => trans("admin.add logos")
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
       if(!userCan('partners')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       // Make Validation
       $this->rules['link'] = 'sometimes|nullable|url|max:200';
       $this->rules['image'] = 'required|image';
       $data = $this->validate($request, $this->rules);
       //Create New Photo
       $destination = "uploads/logos/" . date("Y") . "/" . date("m") . "/";
       $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
       Logo::create($data);
       //Success Message
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('logos.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('partners')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $logo = Logo::findOrFail($id);
        return view('admin.logos.edit', [
            'title' => trans("admin.edit logo"),
            'edit'  => $logo,
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
        if(!userCan('partners')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $logo = Logo::findOrFail($id);
        // Make Validation
        $this->rules['link'] = 'sometimes|nullable|url|max:200';
        $this->rules['image'] = 'sometimes|nullable|image';
        $data = $this->validate($request, $this->rules);
        //Update Data
        if ($request->hasFile('image')) {
           if (file_exists(public_path('uploads/' . $logo->image))) {
               @unlink(public_path('uploads/' . $logo->image));
           }
           $destination = "uploads/logos/" . date("Y") . "/" . date("m") . "/";
           $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
       }
        $logo->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/logos');
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
        if(!userCan('partners')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $logos = Logo::findOrFail($id);
        if ($logos) {
            if (file_exists(public_path('uploads/' . $logos->image))) {
                @unlink(public_path('uploads/' . $logos->image));
            }
            $logos->delete();
        }
    }


}
