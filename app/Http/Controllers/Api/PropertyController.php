<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Traits\AuthGuardTrait;

use App\Helpers\Helper;
use App\Http\Helpers\UploadFile;

use App\Http\Requests\Property\PropertyStoreRequest;
use App\Http\Requests\Property\PropertyUpdateRequest;

use App\Models\User;
use App\Models\SupportTicket;
use App\Models\Conversation;
use App\Models\Language;
use App\Models\Amenity;
use App\Models\BasicSettings\Basic;
use App\Models\Property\Property;
use App\Models\Property\Content;
use App\Models\Property\PropertyContact;
use App\Models\Property\Wishlist;
use App\Models\Property\Area;
use App\Models\Property\City;
use App\Models\Property\Country;
use App\Models\Property\State;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyAmenity;
use App\Models\Property\PropertySliderImage;
use App\Models\Property\PropertyUnit;
use App\Models\Property\Spacification;
use App\Models\Property\SpacificationCotent;
use App\Models\Property\Unit;

use Carbon\Carbon;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\ImageMimeTypeRule;
use App\Models\Vendor;
use App\Http\Helpers\VendorPermissionHelper;

use Purifier;

class PropertyController extends Controller
{
    use ApiResponseTrait;
    use AuthGuardTrait;

    public function index(Request $request)
    { 
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];
       
        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $language = Language::where('is_default', 1)->first(); 
 
        $title = $request->filled('title') ? trim((string) $request->input('title')) : null;

        $data['properties'] = Property::where($column, $user->id)
            ->join('property_contents', 'properties.id', 'property_contents.property_id')
            ->with([
                'propertyContents' => function ($q) use ($language) {
                    $q->where('language_id', $language->id);
                },
                'cityContent' => function ($q) use ($language) {
                    $q->where('language_id', $language->id);
                }
            ])
            ->when($title, function ($query) use ($title) {
                return $query->where('property_contents.title', 'LIKE', '%' . $title . '%');
            })
            ->where('property_contents.language_id', $language->id)
            ->select('properties.*')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $this->successResponse($data);
    }

    public function requestValidation(Request $request, $isUpdate = false)
    {
        $rules = [ 
            'price' => 'nullable|numeric',
            'bath' => 'nullable|required_if:type,residential|numeric|min:0',
            'purpose' => 'required',
            'area' => 'required_if:purpose,franchiese|required_if:purpose,business_for_sale|numeric|min:0',
            'status' => 'required',
            'category_id' => 'required',
            'city_id' => 'required',
            'address' => 'required|max:255',
            'notes' => 'nullable|required_if:purpose,franchiese|required_if:purpose,business_for_sale',
        ]; 
        
        if($isUpdate){
            $rules['featured_image'] = [new ImageMimeTypeRule()];
            $rules['slider_images.*'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }
       
        $rules['country_id'] = 'required';

        // Per-language rules
        $languages = Language::all();
        foreach ($languages as $lang) {
            $code = $lang->code; 
            $rules["{$code}_title"] = 'required|max:255';
            $rules["{$code}_description"] = 'required|min:15'; 
            $rules["{$code}_label"] = 'array'; 
            $rules["{$code}_value"] = 'nullable|array'; 
        }

        // Messages
        $messages = [];
        foreach ($languages as $lang) 
        {
            $code = $lang->code;
            $name = $lang->name;
            $messages["{$code}_title.required"] = "The title field is required for {$name} language.";
            $messages["{$code}_address.required"] = "The address field is required for {$name} language.";
            $messages["{$code}_description.required"] = "The description field is required for {$name} language.";
            $messages["{$code}_description.min"] = "The description  must be at least :min characters for {$name} language.";
            $messages["{$code}_label.max"] = "Additional Features for {$name} language shall not exceed :max for this vendor.";
        }
        $messages['bath.required_if'] = 'The bath field is required.';
        $messages['slider_images.required'] = 'The gallery image field is required.';
        $messages['slider_images.max'] = 'The gallery image shall not exceed :max for this vendor.';
        $messages['category_id.required'] = 'The category field is required.';
        $messages['featured_image.required'] = 'The featured image field is required.';
        $messages['city_id.required'] = 'The city field is required.';
        $messages['country_id.required'] = 'The country field is required.';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) { 
            return $this->errorResponse($validator->errors()->first());
        }

        return null; // valid
    }

    public function createProperty(Request $request)
    {
        // Guard resolution (as you have)
        $auth = $this->resolveAuthGuard($request->auth_type);
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }
        [$guard, $ownerColumn] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }
 
        if ($request->auth_type === 'vendor' || $request->auth_type === 'agent') {
            $vendorId = $request->auth_type === 'vendor'
                ? (int) $user->id
                : (int) ($user->vendor_id ?? 0);

            if ($vendorId > 0) {
                // Read current/next package once
                $currentPkg = VendorPermissionHelper::currPackageOrPending($vendorId);
                $nextPkg = VendorPermissionHelper::nextPackage($vendorId);

                // If no current AND no next package queued -> expired
                if (is_null($currentPkg) && is_null($nextPkg)) {
                    return $this->errorResponse(
                        'Your membership is expired. Please purchase a new package / extend the current package.',
                        403
                    );
                }

                // Enforce property limit only when current package exists and limit > 0
                $limit = (int) ($currentPkg->number_of_property ?? 0);
                if ($limit > 0) {
                    // Count properties owned by the authenticated actor (preserves your original behavior)
                    $ownedCount = Property::where($ownerColumn, $user->id)->count();
                    if ($ownedCount >= $limit) {
                        return $this->errorResponse(
                            'You have reached the limit of properties allowed by your current package. Please upgrade your package to add more properties.',
                            403
                        );
                    }
                }
            }
        }


        if ($resp = $this->requestValidation($request)) {
            return $resp; // return JsonResponse from validator
        }

        DB::beginTransaction();
        try { 
            $languages = Language::all();

            $featuredImgURL = $request->featured_image;
            if (request()->hasFile('featured_image')) {
                $featuredImgName = UploadFile::store(public_path('assets/img/property/featureds'), $featuredImgURL);
            }

            $floorPlanningImage = null;
            $videoImage = null;
            if (request()->hasFile('floor_planning_image')) {
                $floorPlanningImage = UploadFile::store(public_path('assets/img/property/plannings'), $request->floor_planning_image);
            }
            if ($request->hasFile('video_image')) {
                $videoImage = UploadFile::store('assets/img/property/video/', $request->video_image);
            }
   
            $bs = Basic::select('property_approval_status')->first(); 
            if ($bs->property_approval_status == 1) {
                $approveStatus = 0;
            } else {
                $approveStatus = 1;
            }
            $property = Property::create([
                $ownerColumn => $user->id ?? 0,
                'is_new' => '0',
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'featured_image' => $featuredImgName ?? null,
                'floor_planning_image' => $floorPlanningImage,
                'video_image' => $videoImage,
                'price' => $request->price,
                'purpose' => $request->purpose,
                'address' => $request->address,
                'type' => $request->type,
                'notes' => $request->notes ?? '',
                'beds' => $request->beds ?? 0,
                'bath' => $request->bath ?? 0,
                'area' => $request->area ?? 0,
                'video_url' => $request->video_url,
                'status' => $request->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'approve_status' => $approveStatus,
                'furnishing' => $request->furnishing,
                'possession_date' => $request->possession_date 
            ]);

            if ($request->hasFile('slider_images')) { 
                foreach ($request->file('slider_images') as $image) {
                    // Store the file using your custom UploadFile helper
                    $imageName = UploadFile::store(
                        public_path('assets/img/property/slider-images/'),
                        $image
                    );
 
                    // Create slider record
                    PropertySliderImage::create([
                        'property_id' => $property->id ?? null,
                        'image' => $imageName,
                    ]);
                }
            } 

            if ($request->has('amenities')) {
                foreach ($request->amenities as $amenity) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity_id' => $amenity
                    ]);
                }
            }
            if ($request->has('unit_type')) {
                foreach ($request->unit_type as $unit) {
                    PropertyUnit::create([
                        'property_id' => $property->id,
                        'unit_id' => $unit
                    ]);
                }
            }

            foreach ($languages as $language) 
            {
                $propertyContent = new Content();
                $propertyContent->language_id = $language->id;
                $propertyContent->property_id = $property->id;
                $propertyContent->title = $request[$language->code . '_title'];
                $propertyContent->slug = createSlug($request[$language->code . '_title']);
                $propertyContent->address = $request->address;
                $propertyContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                $propertyContent->meta_keyword = $request[$language->code . '_meta_keyword'];
                $propertyContent->meta_description = $request[$language->code . '_meta_description'];
                $propertyContent->save();

                $label_datas = $request[$language->code . '_label'];
                foreach ($label_datas as $key => $data) {
                    if (!empty($request[$language->code . '_value'][$key])) {
                        $property_specification = Spacification::where([['property_id', $property->id], ['key', $key]])->first();
                        if (is_null($property_specification)) {
                            $property_specification = new Spacification();
                            $property_specification->property_id = $property->id;
                            $property_specification->key = $key;
                            $property_specification->save();
                        }
                        $property_specification_content = new SpacificationCotent();
                        $property_specification_content->language_id = $language->id;
                        $property_specification_content->property_spacification_id = $property_specification->id;
                        $property_specification_content->label = $data;
                        $property_specification_content->value = $request[$language->code . '_value'][$key];
                        $property_specification_content->save();
                    }
                }
            } 

            DB::commit();
            return $this->successResponse($property, 'Property created successfully.');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( 'Something went wrong. ' . $e->getMessage());
        }    
    }

    public function updateProperty(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        if ($resp = $this->requestValidation($request, true)) {
            return $resp; // return JsonResponse from validator
        }
        DB::beginTransaction();

        try
        {
            $languages = Language::all(); 
            $property = Property::where($column, $user->id)->findOrFail($request->property_id); 

            $featuredImgName = $property->featured_image;
            $floorPlanningImage = $property->floor_planning_image;
            $videoImage = $property->video_image;

            if ($request->hasFile('featured_image')) {
                $featuredImgName = UploadFile::update('assets/img/property/featureds', $request->featured_image, $property->featured_image);
            }

            if ($request->hasFile('floor_planning_image')) { 
                $floorPlanningImage = UploadFile::update('assets/img/property/plannings', $request->floor_planning_image, $property->floor_planning_image);
            }

            if ($request->hasFile('video_image')) {
                $videoImage = UploadFile::update('assets/img/property/video/', $request->video_image, $property->video_image);
            }

            $property->update([
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'featured_image' => $featuredImgName,
                'floor_planning_image' => $floorPlanningImage,
                'video_image' => $videoImage,
                'price' => $request->price,
                'purpose' => $request->purpose,
                'type' => $request->type,
                'address' => $request->address,
                'notes' => $request->notes ?? '',
                'beds' => $request->beds ?? 0,
                'bath' => $request->bath ?? 0,
                'area' => $request->area ?? 0,
                'video_url' => $request->video_url,
                'status' => $request->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'furnishing' => $request->furnishing,
                'possession_date' => $request->possession_date
            ]);

            $d_property_specifications = Spacification::where('property_id', $request->property_id)->get();
            foreach ($d_property_specifications as $d_property_specification) {
                $d_property_specification_contents = SpacificationCotent::where('property_spacification_id', $d_property_specification->id)->get();
                foreach ($d_property_specification_contents as $d_property_specification_content) {
                    $d_property_specification_content->delete();
                }
                $d_property_specification->delete();
            }

            if ($request->has('amenities')) {
                $property->proertyAmenities()->delete();
                foreach ($request->amenities as $amenity) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity_id' => $amenity
                    ]);
                }
            }

            if ($request->has('unit_type')) {
                $property->proertyUnits()->delete();
                foreach ($request->unit_type as $unit) {
                    PropertyUnit::create([
                        'property_id' => $property->id,
                        'unit_id' => $unit
                    ]);
                }
            } 

            foreach ($languages as $language)
            {
                $propertyContent = Content::where('property_id', $request->property_id)->where('language_id', $language->id)->first();

                if (empty($propertyContent)) {
                    $propertyContent = new Content();
                }

                $propertyContent->language_id = $language->id;
                $propertyContent->property_id = $property->id;
                $propertyContent->title = $request[$language->code . '_title'];
                $propertyContent->slug = createSlug($request[$language->code . '_title']);
                $propertyContent->address = $request->address;
                $propertyContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                $propertyContent->meta_keyword = $request[$language->code . '_meta_keyword'];
                $propertyContent->meta_description = $request[$language->code . '_meta_description'];
                $propertyContent->save();

                $label_datas = $request[$language->code . '_label'];
                foreach ($label_datas as $key => $data) {
                    if (!empty($request[$language->code . '_value'][$key])) {
                        $property_specification = Spacification::where([['property_id', $property->id], ['key', $key]])->first();
                        if (is_null($property_specification)) {
                            $property_specification = new Spacification();
                            $property_specification->property_id = $property->id;
                            $property_specification->key = $key;
                            $property_specification->save();
                        }
                        $property_specification_content = new SpacificationCotent();
                        $property_specification_content->language_id = $language->id;
                        $property_specification_content->property_spacification_id = $property_specification->id;
                        $property_specification_content->label = $data;
                        $property_specification_content->value = $request[$language->code . '_value'][$key];
                        $property_specification_content->save();
                    }
                }
            } 
            DB::commit();
            return $this->successResponse($property, 'Property updated successfully.');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( 'Something went wrong. ' . $e->getMessage());
        }    
    }

    public function removeSliderImage(Request $request)
    {
        // 1) Validate input
        $validator = Validator::make($request->all(), [
            'slider_image_id' => 'required|integer|exists:property_slider_images,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first());
        }

        // 2) Fetch the slider image
        $psi = PropertySliderImage::findOrFail($request->input('slider_image_id'));

        // 3) Count how many images this property has
        $imageCount = PropertySliderImage::where('property_id', $psi->property_id)->count();

        if ($imageCount <= 1) {
            // Business rule: do not allow deleting the last image
            return $this->errorResponse('You must keep at least one slider image for this property.', 400);
        }

        // 4) Delete file (if present)
        $fullPath = public_path('assets/img/property/slider-images/' . $psi->image);
        if (is_string($psi->image) && $psi->image !== '' && file_exists($fullPath)) {
            @unlink($fullPath);
        }

        // 5) Delete DB row
        $psi->delete();

        return $this->successResponse([], 'Property slider image removed.');
    }

    public function deleteProperty($id)
    {
        // 1) Fetch once, fail fast if missing
        $property = Property::select('id', 'featured_image', 'floor_planning_image', 'video_image')
            ->find($id);

        if (!$property) {
            return $this->notFoundResponse('Property not found.');
        }

        // (Optional) ownership/permission check here
        // if (! $this->canDelete($property)) return $this->unauthorizedResponse();

        DB::beginTransaction();

        try {
            // 2) Collect files to delete
            $files = [];
            if (!empty($property->featured_image)) {
                $files[] = public_path('assets/img/property/featureds/' . $property->featured_image);
            }
            if (!empty($property->floor_planning_image)) {
                $files[] = public_path('assets/img/property/plannings/' . $property->floor_planning_image);
            }
            if (!empty($property->video_image)) {
                $files[] = public_path('assets/img/property/video/' . $property->video_image);
            }

            // Slider images: collect paths + delete rows in bulk
            $sliderImages = $property->galleryImages()->select('id', 'image')->get();
            foreach ($sliderImages as $img) {
                if (!empty($img->image)) {
                    $files[] = public_path('assets/img/property/slider-images/' . $img->image);
                }
            }
            if ($sliderImages->isNotEmpty()) {
                PropertySliderImage::whereIn('id', $sliderImages->pluck('id'))->delete();
            }

            // 3) Bulk delete related rows (no loops)
            // amenities & units
            $property->proertyAmenities()->delete(); // relation query delete
            $property->proertyUnits()->delete();

            // specifications + their contents in two statements
            $specIds = $property->specifications()->pluck('id');
            if ($specIds->isNotEmpty()) {
                SpacificationCotent::whereIn('property_spacification_id', $specIds)->delete();
                Spacification::whereIn('id', $specIds)->delete();
            }

            // contents
            $property->propertyContents()->delete();

            // messages, featured, wishlists
            $property->propertyMessages()->delete();
            $property->featuredProperties()->delete();
            $property->wishlists()->delete();

            // 4) Delete the property
            $property->delete();

            DB::commit();

            // 5) Delete files AFTER commit (so data isn’t gone if file ops fail)
            foreach ($files as $path) {
                if (is_string($path) && $path !== '' && file_exists($path)) {
                    @unlink($path);
                }
            }

            return $this->successResponse([], 'Property deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();  
            return $this->errorResponse('Failed to delete property. Please try again later.', 500);
        }
    }

    public function deleteBulkProperty(Request $request)
    {
        $requestedIds = $request->input('ids', []);
        if (!is_array($requestedIds) || empty($requestedIds)) {
            return $this->errorResponse('No property IDs provided for deletion.', 400);
        }
         
        DB::beginTransaction();

        try 
        {
            foreach ($requestedIds as $id) 
            { 
                $property = Property::select('id', 'featured_image', 'floor_planning_image', 'video_image')
                    ->find($id);

                if (!$property) {
                    continue; // skip to next ID
                }

                $files = [];
                if (!empty($property->featured_image)) {
                    $files[] = public_path('assets/img/property/featureds/' . $property->featured_image);
                }
                if (!empty($property->floor_planning_image)) {
                    $files[] = public_path('assets/img/property/plannings/' . $property->floor_planning_image);
                }
                if (!empty($property->video_image)) {
                    $files[] = public_path('assets/img/property/video/' . $property->video_image);
                }

                // Slider images: collect paths + delete rows in bulk
                $sliderImages = $property->galleryImages()->select('id', 'image')->get();
                foreach ($sliderImages as $img) {
                    if (!empty($img->image)) {
                        $files[] = public_path('assets/img/property/slider-images/' . $img->image);
                    }
                }
                if ($sliderImages->isNotEmpty()) {
                    PropertySliderImage::whereIn('id', $sliderImages->pluck('id'))->delete();
                }
 
                $property->proertyAmenities()->delete(); // relation query delete
                $property->proertyUnits()->delete();

                // specifications + their contents in two statements
                $specIds = $property->specifications()->pluck('id');
                if ($specIds->isNotEmpty()) {
                    SpacificationCotent::whereIn('property_spacification_id', $specIds)->delete();
                    Spacification::whereIn('id', $specIds)->delete();
                }

                // contents
                $property->propertyContents()->delete();

                // messages, featured, wishlists
                $property->propertyMessages()->delete();
                $property->featuredProperties()->delete();
                $property->wishlists()->delete();

                // 4) Delete the property
                $property->delete();

                DB::commit();

                // 5) Delete files AFTER commit (so data isn’t gone if file ops fail)
                foreach ($files as $path) {
                    if (is_string($path) && $path !== '' && file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
            return $this->successResponse([], 'Properties deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();  
            return $this->errorResponse('Failed to delete property. Please try again later.', 500);
        }
    }

    public function editProperty($id)
    {
        // 1) Fetch once, fail fast if missing
        $property = Property::with([
            'propertyContents',
            'proertyAmenities',
            'proertyUnits',
            'specifications.specificationContents',
            'galleryImages'
        ])->find($id);

        if (!$property) {
            return $this->notFoundResponse('Property not found.');
        }

        return $this->successResponse($property);
    }
}