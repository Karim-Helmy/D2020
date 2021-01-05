<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscriber;

class SubscribersController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id = auth()->user()->subscriber_id;
        $subscriber = Subscriber::findOrFail($id);
        return view('super.subscribers.edit', [
            'title'  => trans('admin.edit'),
            'edit'  => $subscriber,
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
        $subscriber = Subscriber::findOrFail($id);
        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'required';
        $this->rules['image'] = 'sometimes|nullable|image';
        $this->rules['images.*'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        $this->rules['school'] = 'required|max:200';
        $data = $this->validate($request, $this->rules);
        //Upload Logo
        if ($request->hasFile('image')) {
           if (file_exists(public_path('uploads/' . $subscriber->logo))) {
               @unlink(public_path('uploads/' . $subscriber->logo));
           }
           $destination = "uploads/" . $id . "/logos/" . date("Y") . "/" . date("m") . "/";
           $data['logo'] = UploadImages($destination, $request->file('image')); // Upload Image
       }
       $dest = "uploads/" . $id . "/sliders/" . date("Y") . "/" . date("m") . "/";
        $data = array_merge($request->except('_token', '_method'), $data);
        $data['photos'] = uploadMultiImages($request->file('images'), $request->oldImages, $subscriber->photos,$dest);
        //Update Data
        $subscriber->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect()->back();
    }




}
