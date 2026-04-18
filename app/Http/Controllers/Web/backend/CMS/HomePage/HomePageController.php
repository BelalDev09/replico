<?php

namespace App\Http\Controllers\Web\backend\CMS\HomePage;

use App\Enums\Page;
use App\Enums\Section;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Home\HomePageCategorySectionRequest;
use App\Http\Requests\Cms\Home\HomePageTopSectionRequest;
use App\Models\CMS;
use App\Traits\apiresponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomePageController extends Controller
{
    use apiresponse;

    // Top Section
    public function topSection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::TopSection,
        ]);

        return view('backend.layout.cms.home_page.top_section', compact('data'));
    }

    public function topSectionUpdate(HomePageTopSectionRequest $request)
    {
        try {
            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::TopSection,
            ]);

            $oldImage = $data->image;

            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;

            $shouldDeleteOldImage = false;

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if ($file->isValid()) {
                    $shouldDeleteOldImage = true;

                    $nameForFile = Str::slug($request->title ?? 'top-section')
                        . '-image-' . time();

                    $uploadedPath = Helper::fileUpload($file, 'images', $nameForFile);
                    $data->image = $uploadedPath;
                }
            }



            if ($shouldDeleteOldImage && $oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }
            $data->status = 'active';
            $data->save();

            return redirect()->back()->with('notify-success', 'Top section updated successfully');
        } catch (Exception $e) {
            Log::error('Top section update failed: ' . $e->getMessage());

            if (isset($uploadedPath) && file_exists(public_path($uploadedPath))) {
                @unlink(public_path($uploadedPath));
            }

            return redirect()->back()
                ->withInput()
                ->with('notify-error', 'Error: ' . $e->getMessage());
        }
    }

    // Category Section
    public function categorySection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::CategorySection,
        ]);
        $data->main_text = $data->main_text ?? '';
        $data->v1 = json_decode($data->v1 ?? '[]', true);
        $data->v2 = json_decode($data->v2 ?? '[]', true);
        $data->v3 = json_decode($data->v3 ?? '[]', true);

        return view('backend.layout.cms.home_page.category_section', compact('data'));
    }

    public function categorySectionUpdate(HomePageCategorySectionRequest $request)
    {
        try {

            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::CategorySection,
            ]);

            $oldV1 = json_decode($data->v1 ?? '[]', true);
            $oldV2 = json_decode($data->v2 ?? '[]', true);
            $oldV3 = json_decode($data->v3 ?? '[]', true);

            $blocks = [&$oldV1, &$oldV2, &$oldV3];
            $newData = [];

            for ($i = 0; $i < 3; $i++) {

                $title = $request->title[$i] ?? null;

                if (!$title) {
                    $newData[$i] = [];
                    continue;
                }

                $oldImagePath = $blocks[$i]['image'] ?? null;
                $imagePath = $oldImagePath;

                if (!empty($request->file('image')[$i]) && $request->file('image')[$i]->isValid()) {

                    if ($oldImagePath && file_exists(public_path($oldImagePath))) {
                        unlink(public_path($oldImagePath));
                    }

                    $file = $request->file('image')[$i];

                    $name = 'category-v' . ($i + 1) . '-' . Str::slug($title) . '-' . uniqid();

                    $imagePath = Helper::fileUpload($file, 'images', $name);
                }

                $newData[$i] = [
                    'title' => $title,
                    'sub_title' => $request->sub_title[$i] ?? null,
                    'image' => $imagePath,
                    'button_text' => $request->button_text[$i] ?? null,
                    'button_link' => $request->button_link[$i] ?? null,
                ];
            }
            $data->main_text = $request->main_text;

            $data->v1 = json_encode($newData[0] ?? []);
            $data->v2 = json_encode($newData[1] ?? []);
            $data->v3 = json_encode($newData[2] ?? []);

            $data->status = $request->status ?? 'active';
            $data->save();

            return back()->with('notify-success', 'Category section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }
}
