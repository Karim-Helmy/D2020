<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Logo;
use App\Course;
use App\User;
use App\Contact;
use App\Package;
use App\AboutPage;
use App\Subscriber;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function index()
    {
        $courses = Course::with('subscriber')->where('status', '1')->limit(3)->orderBy('id','desc')->get();
        $partners = Logo::limit(20)->get();
        $school_count = Subscriber::count();
        $student_count = User::where('type','3')->count();
        $course_count = Course::count();
        return view('welcome',[
            'courses' => $courses,
            'partners' => $partners,
            'school_count' => $school_count,
            'student_count' => $student_count,
            'course_count' => $course_count,
        ]);
    }

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function about()
    {
        $about = AboutPage::findOrFail('1');
        return view('about',compact('about'));
    }

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function apps()
    {
        return view('apps');
    }

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function service()
    {
        return view('services');
    }

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function terms()
    {
        return view('terms');
    }



    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function contact()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        //Validation
        $data = $this->validate($request, [
        'name'=>'required|max:150',
        'email'=>'required|max:150|email',
        'subject'=>'required|max:150',
        'message'=>'required|max:150',
        'g-recaptcha-response' => 'required|captcha',
        ]);
        //Send Message
        Contact::create($data);
        session()->flash('success', trans("admin.sent Successfully"));
        return back();
    }

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function prices()
    {
        $packages = Package::with('option','category')->get();
        return view('prices',compact('packages'));
    }

    public function subscribe(Request $request)
    {
        //Validation
        $this->rules['name'] = 'required|max:250';
        $this->rules['email'] = 'required|email|unique:subscribers,email';
        $this->rules['phone'] = 'required|regex:/(05)[0-9]{8}/|size:10';
        $this->rules['address'] = 'required';
        $this->rules['school'] = 'required';
        $this->rules['description'] = 'sometimes||nullable';
        $this->rules['package_id'] = 'required|exists:packages,name';
        $data = $this->validate($request, $this->rules);
        if(strpos($request->package_id,"مبتكر")){
            $data['status'] = '1';
        }else{
            $data['status'] = '0';
        }
        $data['package_id'] = Package::where('name',$request->package_id)->first()->id;
        //Create Subscriber
        Subscriber::create($data);
        session()->flash('success', trans("admin.subscribe Successfully"));
        return back();
    }
}
