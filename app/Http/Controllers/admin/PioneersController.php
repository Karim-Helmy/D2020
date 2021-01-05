<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pioneer;
use App\Category;
use App\Rules\ValidateYoutube;

class PioneersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('pioneers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $categories = Category::all();
        $pioneer = Pioneer::with('category');
        if(request()->filled('category')){
            $pioneer->where('category_id',request()->category);
        }
        $pioneers = $pioneer->paginate(12);
        return view('admin.pioneers.index', [
            'title' => trans("admin.show all pioneers"),
            'index' => $pioneers,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('pioneers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $categories = Category::all();
        return view('admin.pioneers.create', [
            'title' => trans("admin.add pioneers"),
            'categories' => $categories,
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
       if(!userCan('pioneers')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       // Make Validation
       $this->rules['link'] = 'required|url';
       $this->rules['link'] = [new ValidateYoutube];
       $this->rules['category_id'] = 'required|exists:categories,id';
       $data = $this->validate($request, $this->rules);
       //Create Pioneer Video
       Pioneer::create($data);
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('pioneers.create');
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('pioneers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $pioneers = Pioneer::where('id',$id)->firstOrFail();
        $categories = Category::all();
        return view('admin.pioneers.edit', [
            'title' => trans("admin.edit pioneers"),
            'edit'  => $pioneers,
            'categories' => $categories,
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
        if(!userCan('pioneers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        // Make Validation
        $this->rules['link'] = 'required|url';
        $this->rules['link'] = [new ValidateYoutube];
        $this->rules['category_id'] = 'required|exists:categories,id';
        $data = $this->validate($request, $this->rules);

        //Update Data
        Pioneer::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/pioneers');
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
         if(!userCan('pioneers')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $pioneer = Pioneer::where('id',$id)->firstOrFail();
         $pioneer->delete();
         return  redirect()->back();
     }


}
