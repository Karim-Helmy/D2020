<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;


class SettingsController extends Controller
{
    private $rules = [
        'key'     => 'required|max:250',
        'type'     => 'required|in:text,longtext,image',
    ];

    private $rulesUpdate = [
    ];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

      $setting = Setting::all();
        return view('admin.settings.create', [
            'title' => "Create setting",
            'setting' => $setting,
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
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $data = $this->validate($request, $this->rules);
        $setting = Setting::create($data);
        session()->flash('success', "Created Successfully");
        return redirect()->route('settings.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function index()
     {
         if(!userCan('settings')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $pages = Setting::all();

         return view('admin.settings.index', [
             'title' => trans('admin.show all settings'),
             'index' => $pages,
         ]);
     }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $setting = Setting::find($id);
        if ($setting) {
            return view('admin.settings.edit', [
                'title' => trans('admin.edit settings'),
                'edit'  => $setting,
            ]);
        }
        return back();
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
        if(!userCan('settings')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $setting = Setting::find($id);
        if (!$setting) {
            return back();
        }
        $data = $this->validate($request, $this->rulesUpdate);
        if ($setting->type == "image") {
        if ($request->hasFile('value')) {
            if (file_exists(public_path('uploads/' . $setting->value))) {
                @unlink(public_path('uploads/' . $setting->value));
            }
            $data['value'] = UploadImages('settings', $request->file('value'));
        }
        $data = array_merge($request->except('_token', '_method'), $data);
      }else{
        $data['value'] = $request->value;
      }

        $setting->update($data); // true, false

        session()->flash('success', trans('admin.edit Successfully'));
        return redirect()->route('settings.index');
    }



}
