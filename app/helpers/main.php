<?php
use App\Message;

/**
* [sendResponse description]
* @param  [string] $message [message success]
* @param  [array] $data    [any data object or array]
* @return [json]          [json success]
*/
function sendResponse($message,$data){
    return response([
        "status" => true,
        "message" => $message,
        "data"    => $data,
    ],200);
}
/**
* [sendError description]
* @param  [string] $message [message error]
* @return [array]          [errors]
*/
function sendError($message){
    return response([
        "status" => false,
        "message" => $message,
    ],400);
}
/**
* [aurl To Go To Admin Url]
* @param  [string] $url [link]
* @return [string]      [/admin.$url]
*/
function aurl($url)
{
    return url('/admin' . $url);
}

/**
* [eurl To Go To Employer Url]
* @param  [string] $url [link]
* @return [string]      [/admin.$url]
*/
function eurl($url)
{
    return url('/employer' . $url);
}

/**
* [aurl To Go To Super Url]
* @param  [string] $url [link]
* @return [string]      [/super.$url]
*/
function surl($url)
{
    return url('/super' . $url);
}

/**
* [aurl To Go To Trainer Url]
* @param  [string] $url [link]
* @return [string]      [/trainer.$url]
*/
function turl($url)
{
    return url('/trainer' . $url);
}

/**
* [aurl To Go To Student Url]
* @param  [string] $url [link]
* @return [string]      [/student.$url]
*/
function sturl($url)
{
    return url('/student' . $url);
}

/**
* [UploadImages]
* @param [folder] $dir   [FolderName]
* @param [string] $image [Name Of The Image]
*/
function UploadImages($dir, $image)
{
    $saveImage = '';
    if (! File::exists(public_path('uploads').'/' . $dir)) { // if file or folder not exists
        /**
        *
        * @param $PATH Required
        * @param $mode Defualt 0775
        * @param create the directories recursively if doesn't exists
        */
        File::makeDirectory(public_path('uploads') . '/' . $dir, 0775, true); // create the dir or the
    }
    if (File::isFile($image)) {
        $name       = $image->getClientOriginalName(); // get image name
        $extension  = $image->getClientOriginalExtension(); // get image extension
        $sha1       = sha1($name); // hash the image name
        $fileName   = rand(1, 1000000) . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the image
        // get the image realpath
        $uploadedImage = Image::make($image->getRealPath());
        $uploadedImage->save(public_path('uploads/' . $dir . '/' . $fileName), '100'); // save the image
        $saveImage = $dir . '/' . $fileName; // get the name of the image and the dir that uploaded in
    }
    return $saveImage;
}

/**
* [uploadMultiImages ]
* @param  [type] $images   [Request Image]
* @param  [type] $old      [Old Image]
* @param  [type] $elImages [images in database]
* @param  [type] $destination [Destination]
* @return [type]           [Upload Multi Image]
*/
function uploadMultiImages($images, $old, $elImages, $destination)
{
    unlinkRemovedImages($old, $elImages);
    $imgs = [];
    if (!is_null($images)) {
        foreach ($images as $i) {
            $imgs[] = UploadImages($destination, $i);
        }
    }
    if(empty($old)){
        $old = [];
    }
    return implode('|', array_merge($old, $imgs));
}


/**
* [UpdateImages]
* @param [string] $oldFile [old image]
* @param [folder] $dir     [folder of image]
* @param [string] $image   [new image]
*/
function UpdateImages($oldFile, $dir, $image) {
    if ( !empty($oldFile) && !is_null($oldFile) && file_exists( public_path('uploads/'.$oldFile) ) ) {
        unlink(public_path('uploads/'.$oldFile));
    }
    return UploadImages($dir, $image);
}
/**
* [checkValue]
* @param  [string] $value [Check if it Found Or No]
* @return [Bollean]        [True Or False]
*/
function checkValue($value)
{
    return !empty($value) && !is_null($value);
}

/**
* [unlinkRemovedImages]
* @param  [type] $old      [description]
* @param  [type] $elImages [description]
* @return [type]           [description]
*/
function unlinkRemovedImages($old, $elImages)
{
    $elImages = explode('|', $elImages);

    $removedImages = array_diff($elImages, $old ?? []);

    if (count($removedImages) && !is_null($removedImages)) {
        foreach ($removedImages as $i) {
            if (!is_null($i) && !empty($i)) {
                @unlink('uploads/'.$i);
            }
        }
    }
}


/**
* [getUserIP]
* @return [ip] [IP Address]
*/
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

/**
* [GetSetting]
* @param [type] $key [Get Value Of This Key]
*/
function GetSetting($key) {
    try {
        $s = App\Setting::where('key', $key)->first();
        return $s->type != 'image' ? $s->value : asset('uploads/'.$s->value);
    } catch (\Exception $e) {
        throw new \Exception("Error In Settings $key Dose not exist", 1);

    }
}

/**
 * [GetMessage]
 */
function GetMessage() {
    try {
        $s = [];
        $s = App\Message::where('receiver_id',auth()->id())->where('views','0')->orderBy('id', 'desc')->limit(5)->get();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [CountMessage]
 */
function CountMessage() {
    try {
        $s = App\Message::where('receiver_id',auth()->id())->where('views','0')->count();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [GetMessage]
 */
function GetMessageAdmin() {
    try {
        $s = [];
        $s = App\Message::whereNull('receiver_id')->where('views','0')->orderBy('id', 'desc')->limit(10)->get();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);
    }
}

/**
 * [CountMessage]
 */
function CountMessageAdmin() {
    try {
        $s = App\Message::whereNull('receiver_id')->where('views','0')->count();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [GetMessage of Visitors]
 */
function GetMessageVisitors() {
    try {
        $s = App\Contact::where('views','like','0')->orderBy('id', 'desc')->get();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [CountMessage of Visitors]
 */
function CountMessageVisitors() {
    try {
        $s = App\Contact::where('views','like','0')->orderBy('id', 'desc')->count();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [Count InActive Supervisors]
 */
function CountSupervisor() {
    try {
        $s = App\Subscriber::where('status','0')->count();
        return $s;
    } catch (\Exception $e) {
        throw new \Exception("Error In Message Dose not exist", 1);

    }
}

/**
 * [GetLogo School]
 */
function GetLogo() {
    try {
        $logo = App\Subscriber::where('id',auth()->user()->subscriber_id)->first()->logo;
        return $logo;
    } catch (\Exception $e) {
        throw new \Exception("Error In Logo Dose not exist", 1);

    }
}


/**
* [Permission]
* @param  [type] $permission [description]
* @param  [type] $id         [description]
* @return [type]             [description]
*/
function userCan($permission, $id = null) {
        if (!is_null($id)) {
            $user = App\Admin::find($id);
        } else {
            $user = auth()->guard('webAdmin')->user();
        }
        if (checkValue($user->roles)) {
            $role = $user->roles->pluck('name')->toArray();
            $data = [$permission,'admin'];
            $result =  array_intersect($data,$role);
            if($result){
                return true;
            }else{
                return false;
            }
        }

        return false;
    }


    /**
    * [Subscribers]
    * @param  [type] $subscriber->id [description]
    * @param  [type] $id         [description]
    * @return [type]             [description]
    */
    function userSubscriber($subscriber_id) {
                $user = auth()->guard('webAdmin')->user();
            if (checkValue($user->subscriber)) {
                $subscriber = $user->subscriber->pluck('id')->toArray();
                $data = [$subscriber_id];
                $result =  array_intersect($data,$subscriber);
                if($result){
                    return true;
                }else{
                    return false;
                }
            }

            return false;
        }




/**
* [Get Value Of Packages Options]
* @param [type] $key [Get Value Of This Key]
*/
function GetPackage($option_id,$package_id) {
    try {
        $package_option = App\PackageOption::where('option_id', $option_id)->where('package_id',$package_id)->first();
        return $package_option->value ?? "55555";
    } catch (\Exception $e) {
        throw new \Exception("Not exist", 1);

    }
}


function removeNamespaceFromXML( $xml )
{
    // Because I know all of the the namespaces that will possibly appear in
    // in the XML string I can just hard code them and check for
    // them to remove them
    $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
    // This is part of a regex I will use to remove the namespace declaration from string
    $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

    // Cycle through each namespace and remove it from the XML string
    foreach( $toRemove as $remove ) {
        // First remove the namespace from the opening of the tag
        $xml = str_replace('<' . $remove . ':', '<', $xml);
        // Now remove the namespace from the closing of the tag
        $xml = str_replace('</' . $remove . ':', '</', $xml);
        // This XML uses the name space with CommentText, so remove that too
        $xml = str_replace($remove . ':commentText', 'commentText', $xml);
        // Complete the pattern for RegEx to remove this namespace declaration
        $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
        // Remove the actual namespace declaration using the Pattern
        $xml = preg_replace($pattern, '', $xml, 1);
    }

    // Return sanitized and cleaned up XML with no namespaces
    return $xml;
}

function namespacedXMLToArray($xml)
{
    // One function to both clean the XML string and return an array
    return json_decode(json_encode(simplexml_load_file(removeNamespaceFromXML($xml))), true);

}
