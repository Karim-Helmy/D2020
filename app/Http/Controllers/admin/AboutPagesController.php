<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AboutPage;

class AboutPagesController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $pages = AboutPage::where('id','1')->firstOrFail();
        return view('admin.about.edit', [
            'title' => trans("admin.about_us"),
            'edit'  => $pages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $pages = AboutPage::findOrFail('1');
        // Make Validation
        $this->rules['vision_ar'] = 'required';
        $this->rules['vision_en'] = 'required';
        $this->rules['mission_ar'] = 'required';
        $this->rules['mission_en'] = 'required';
        $this->rules['vision_photo'] = 'sometimes|nullable|image';
        $this->rules['mission_photo'] = 'sometimes|nullable|image';
        $data = $this->validate($request, $this->rules);
        // If Upload File File
        // Delete Old File
        if ($request->hasFile('vision_photo')) {
           if (file_exists(public_path('uploads/' . $pages->vision_photo))) {
               @unlink(public_path('uploads/' . $pages->vision_photo));
           }
           $destination = "uploads/about/" . date("Y") . "/" . date("m") . "/";
           $data['vision_photo'] = UploadImages($destination, $request->file('vision_photo')); // Upload Image
       }
       if ($request->hasFile('mission_photo')) {
          if (file_exists(public_path('uploads/' . $pages->mission_photo))) {
              @unlink(public_path('uploads/' . $pages->mission_photo));
          }
          $destination = "uploads/about/" . date("Y") . "/" . date("m") . "/";
          $data['mission_photo'] = UploadImages($destination, $request->file('mission_photo')); // Upload Image
      }
        //Update Data
        $pages->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect()->back();
    }


}
