<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PackageCategory;
use App\Category;
use App\Option;
use App\Package;
use App\Subscriber;
use App\PackageOption;
class PackagesController extends Controller
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

        return view('admin.packages.index', [
            'title' => trans("admin.show all packages"),
            'index' => Package::all()
        ]);
    }

    /**
     * [show Details Of packages]
     * @param  [int] $id [Id of packages]
     * @return [array]     [description]
     */
    public function show($id)
    {
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $today = date('Y-m-d');
        // For Statistics
        $subscibers = Subscriber::where('package_id',$id)->get();
        $subsciber_all = Subscriber::where('package_id',$id)->count();
        $subscibers_agree_get = Subscriber::where('package_id',$id)->where('status','1')->get();
        $subsciber_agree = $subscibers_agree_get->count();
        $subscibers_waiting_get = Subscriber::where('package_id',$id)->where('status','0')->get();
        $subsciber_waiting = $subscibers_waiting_get->count();
        $subsciber_today = Subscriber::where('package_id',$id)->whereDate('created_at',$today)->count();
        // Return Data
        return view('admin.packages.show', [
            'title' => trans("admin.packages"),
            'package' => Package::with('option')->where('id',$id)->firstOrFail(),
            // For Statistics
            'subscibers_agree_get' => $subscibers_agree_get,
            'subscibers_waiting_get' => $subscibers_waiting_get,
            'subsciber_all' => $subsciber_all,
            'subsciber_agree' => $subsciber_agree,
            'subsciber_waiting' => $subsciber_waiting,
            'subsciber_today' => $subsciber_today,
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

        $options = Option::all();
        $categories = Category::all();
        return view('admin.packages.create', [
            'title' => trans("admin.add package"),
            'options' => $options,
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
       if(!userCan('packages')){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }

       //Make Validation
       $this->rules['name'] = 'required|max:200';
       $this->rules['price'] = 'required|integer';
       $this->rules['option'] = 'required|array';
       $this->rules['category_id'] = 'required|array';
       $data = $this->validate($request, $this->rules);
       //Create New Packages
       $package = Package::create($data);
       //Add Options To This Packages
       foreach ($data['option'] as $key => $value) {
           if($value){
               PackageOption::create([
                   'package_id' => $package->id,
                   'option_id'  => (int)$key,
                   'value'      => "$value"
               ]);
           }
       }
       //Add Categories To This Packages
       $package->category()->attach($request->category_id);
       //Success Messages
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->route('packages.create');
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

        $package = Package::where('id',$id)->with('option','category')->firstOrFail();
        $options = Option::all();
        $categories = Category::all();
        return view('admin.packages.edit', [
            'title' => trans("admin.edit package") . ' : ' . $package->name,
            'edit'  => $package,
            'options'  => $options,
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
        if(!userCan('packages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $package = Package::findOrFail($id);
        // Make Validation
        $this->rules['name'] = 'required|max:200';
        $this->rules['price'] = 'required|integer';
        $this->rules['category_id'] = 'required|array';
        $data = $this->validate($request, $this->rules);
        //Update Data
        $package->update($data);
        //Add Options To This Packages
        foreach ($request->option as $key => $value) {
                PackageOption::where('option_id',$key)->where('package_id',$id)->update([
                    'value'      => "$value"
                ]);
        }
        //Add Categories To This Packages
        $package->category()->sync($request->category_id);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/packages');
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
        $package = Package::findOrFail($id);
        if ($package) {
            $package->delete();
        }
    }


}
