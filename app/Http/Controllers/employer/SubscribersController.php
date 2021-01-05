<?php
namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\User;
use Hash;

class SubscribersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriber = Admin::where('id',auth()->guard('webAdmin')->id())->with('subscriber.package')->firstOrFail();
        return view('employer.subscribers.index', [
            'title' => trans("admin.show all subscribers"),
            'index' => $subscriber
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $subscriber = User::where('subscriber_id',$id)->firstOrFail();
        return view('employer.subscribers.password', [
            'title' => trans("admin.edit subscriber") . ' : ' . $subscriber->name,
            'edit'  => $subscriber,
        ]);
    }

    /**
     * Update Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        // Make Validation
        $data = $this->validate($request, [
            'username'=>'required|max:150|unique:users,username,'.$id,
            'password'=>'required|min:6|confirmed',
        ]);
        $data['password'] = Hash::make($request->password);
        //Update Data
        $subscriber = User::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect()->back();
    }



}
