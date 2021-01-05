<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        return view('admin.options.index', [
            'title' => trans("admin.show all options"),
            'index' => Option::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        return view('admin.options.create', [
            'title' => trans("admin.add option"),
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
       if(!userCan('packages')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       $this->rules['name'] = 'required|unique:options,name';
       $data = $this->validate($request, $this->rules);
      Option::create($data);
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('options.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $option = Option::findOrFail($id);
        return view('admin.options.edit', [
            'title' => trans("admin.edit option") . ' : ' . $option->name,
            'edit'  => $option,
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
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        // Make Validation
        $this->rules['name'] = 'required|unique:options,name,'.$id;
        $data = $this->validate($request, $this->rules);
        //Update Data
        Option::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/options');
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
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        if (request()->filled('id')) {
            $id = request()->id;
        }
        if($id != '1'){
            $option = Option::findOrFail($id);
            if ($option) {
                $option->delete();
            }
        }
    }


}
