<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Contact;


class ContactsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('messages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $contact = Contact::orderBy('id', 'desc')->get();
        return view('admin.contacts.index', [
            'title' => trans('admin.show all messages'),
            'index' => $contact,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!userCan('messages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $contact = Contact::find($id);
        $contact->update(['views' => 1]); // true, false
        if ($contact) {
            return view('admin.contacts.show', [
                'title' => trans("admin.show message") . ' : ' . $contact->name,
                'show'  => $contact,
            ]);
        }
        return back();
    }


    public function destroy($id)
    {
        if(!userCan('messages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
            session()->flash('success', trans("admin.delete Successfully"));
            return redirect()->back();
        }
    }


}
