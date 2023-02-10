<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppVersionSettingController extends Controller
{
    public function index()
    {
        $versions = AppVersionSetting::get();
        return view('appVersionSetting.app_version_settings', compact('versions'));
    }

    public function update(Request $request)
    {
//        dd($request->all());
        try {

            $validator = $request->validate([
                'built1' => 'required',
                'version1' => 'required',
                'built2' => 'required',
                'version2' => 'required',
            ]);

            $version1 = AppVersionSetting::find($request->id1);
            $version1->built_number = $request->built1;
            $version1->app_version = $request->version1;
            $version1->save();

            $version2 = AppVersionSetting::find($request->id2);
            $version2->built_number = $request->built2;
            $version2->app_version = $request->version2;
            $version2->save();

            return redirect()->route('admin.app_version_settings')->with('success', __('App Version Settings has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.app_version_settings')->with('error', $exception->getMessage());
        }



    }
}
