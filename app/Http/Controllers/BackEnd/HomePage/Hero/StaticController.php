<?php

namespace App\Http\Controllers\BackEnd\HomePage\Hero;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Hero\HeroStatic;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function index(Request $request)
    {


        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;

        $information['langs'] = Language::all();


        // $information['heroImg'] = Basic::query()->pluck('hero_static_img')->first();
        $heroImg = Basic::query()->pluck('hero_static_img')->first();
        $information['heroImg'] = $heroImg ? json_decode($heroImg, true) : [];

        $information['heroInfo'] = $language->heroStatic()->first(); 
        return view('backend.home-page.hero-section.static-version.index', $information);
    }

    public function updateImage(Request $request)
	{
		try {
			// ✅ Validate request
			$request->validate([
				'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB per file
			]);

			// ✅ Prepare directory
			$directory = public_path('assets/img/hero/static/');
			if (!is_dir($directory)) {
				mkdir($directory, 0775, true);
			}

			// ✅ Fetch existing images
			$basic = Basic::firstOrCreate(['uniqid' => 12345]);
			$existingImages = json_decode($basic->hero_static_img, true) ?? [];

			// ✅ Upload and append new images
			foreach ($request->file('images', []) as $file) {
				$filename = uniqid('hero_') . '.' . $file->getClientOriginalExtension();
				$file->move($directory, $filename);
				$existingImages[] = $filename;
			}

			// ✅ Update database
			$basic->update([
				'hero_static_img' => json_encode(array_values($existingImages)),
			]);

			return back()->with('success', 'Images updated successfully.');
		} 
		catch (\Illuminate\Validation\ValidationException $e) {
			return back()->withErrors($e->errors())->withInput();
		} 
		catch (\Exception $e) {
			\Log::error('Hero Image Update Failed: ' . $e->getMessage());
			return back()->with('error', 'Something went wrong while updating images.');
		}
	}

	
	public function removeImage(Request $request)
	{
		$imageName = $request->input('image_name');
		$directory = public_path('assets/img/hero/static/');

		$basic = Basic::first();
		$images = json_decode($basic->hero_static_img, true);

		if (($key = array_search($imageName, $images)) !== false) {
			unset($images[$key]);
			@unlink($directory . $imageName);
		}

		$basic->update(['hero_static_img' => json_encode(array_values($images))]);

		return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
	}


    public function updateInformation(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->first();

        HeroStatic::query()->updateOrCreate(
            ['language_id' => $language->id],
            [
                'title' => $request->title,
                'text' => $request->text
            ]
        );

        $request->session()->flash('success', 'Information updated successfully!');

        return redirect()->back();
    }
}
