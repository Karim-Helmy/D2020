<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use File;
use Image;

class CitiesController extends Controller
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
        if(!userCan('cities')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        return view('admin.cities.index', [
            'title' => trans("admin.show all cities"),
            'index' => City::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('cities')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        return view('admin.cities.create', [
            'title' => trans("admin.add City"),
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
       if(!userCan('cities')){
          return redirect('admin/index')->with([
          'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
          ]);
      }
       $this->rules['name'] = 'required|unique:cities,name';
       $this->rules['parent'] = 'sometimes|nullable|integer';

       $data = $this->validate($request, $this->rules);

       $data = new City();


       $data->name = $request->name;
       $data->save();
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('cities.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('cities')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        $City = City::findOrFail($id);
        return view('admin.cities.edit', [
            'title' => trans("admin.edit City") . ' : ' . $City->name,
            'edit'  => $City,
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
        if(!userCan('cities')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        // Make Validation
        $this->rules['name'] = 'required|unique:cities,name,'.$id;
        $data = $this->validate($request, $this->rules);
        //Update Data
        City::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/cities');
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
        if(!userCan('cities')){
           return redirect('admin/index')->with([
           'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $City = City::findOrFail($id);
        if ($City) {
            $City->delete();
        }
    }


}
