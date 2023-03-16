<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:settings.management', ['only' => ['setting','updateSetting']]);

    }
    public function setting(){
        $data = Settings::where('id',1)->first();
        return view('admin.setting.setting', compact('data'));

    }
    public function updateSetting(Request $request){

        $request->validate([
            'app_name' => 'required',
            'phone_no' => 'required|min:11',
            'address' => 'required|string',


            'website' => 'required',

           ]);

          $data = Settings::latest()->first();
          $data->app_name = $request->app_name;
          $data->address = $request->address;
          $data->phone_no = $request->phone_no;
          $data->description = $request->description;
          $data->website = $request->website;
          $data->footer_text = $request->footer_text;

          //for upload logo

          if($request->hasFile('logo')){

            $request->validate([

                'logo' =>'mimes:png,jpg,jpeg,gif'
             ]);
             if(file_exists(public_path('uploads/'.$data->logo))){
                unlink(public_path('uploads/'.$data->logo));
             }
             $data->logo =  imageUp($request->logo);
            }

            //upload file for favicon

            if($request->hasFile('favicon')){

                $request->validate([

                    'favicon' =>'mimes:png,jpg,jpeg,gif'
                    ]);
                    if(file_exists(public_path('uploads/'.$data->favicon))){
                        unlink(public_path('uploads/'.$data->favicon));
                    }
                    $favicon = imageUp($request->favicon);
                  $data->favicon =  $favicon;

              }
            $data->update();

              return redirect()->back()->with('success','Update successfully');

    }

    public function index(){
        return redirect()->route('login');
    }
}
