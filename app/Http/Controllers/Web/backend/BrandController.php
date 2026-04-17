<?php

namespace App\Http\Controllers\Web\backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Brand::query();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bulk_check', function ($row) {
                    return '<input type="checkbox" class="select_data" value="' . $row->id . '">';
                })

                ->editColumn('logo', function ($row) {
                    return $row->logo
                        ? '<img src="' . asset($row->logo) . '" width="50">'
                        : 'No Logo';
                })

                ->editColumn('name', fn($row) => $row->name)

                ->addColumn('status', function ($row) {
                    return '
        <div class="form-check form-switch">
            <input type="checkbox"
                class="form-check-input"
                onclick="changeStatus(' . $row->id . ')"
                ' . ($row->status ? 'checked' : '') . '>
        </div>
    ';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.brands.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button onclick="deleteBrand(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>
                    ';
                })

                ->rawColumns(['bulk_check', 'logo', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.brands.index');
    }

    public function create()
    {
        return view('backend.layout.brands.create');
    }

    public function store(BrandRequest $request)
    {
        $logoPath = null;
        $imagePath = null;
        $bannerPath = null;



        if ($request->hasFile('logo')) {
            $logoPath = Helper::fileUpload(
                $request->file('logo'),
                'brands',
                Str::slug($request->name) . '_logo'
            );
        }
        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'brands',
                Str::slug($request->name) . '_image'
            );
        }

        if ($request->hasFile('banner')) {
            $bannerPath = Helper::fileUpload(
                $request->file('banner'),
                'brands',
                Str::slug($request->name) . '_banner'
            );
        }
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logoPath,
            'image' => $imagePath,
            'banner' => $bannerPath,
            'description' => $request->description,
            'country' => $request->country,
            'website' => $request->website,
            'status' => true,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.layout.brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $logo = $brand->logo;
        $image = $brand->image;
        $banner = $brand->banner;

        if ($request->hasFile('logo')) {
            $logo = Helper::fileUpload($request->file('logo'), 'brands', Str::slug($request->name) . '_logo');
        }

        if ($request->hasFile('image')) {
            $image = Helper::fileUpload($request->file('image'), 'brands', Str::slug($request->name) . '_image');
        }

        if ($request->hasFile('banner')) {
            $banner = Helper::fileUpload($request->file('banner'), 'brands', Str::slug($request->name) . '_banner');
        }

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logo,
            'image' => $image,
            'banner' => $banner,
            'description' => $request->description,
            'country' => $request->country,
            'website' => $request->website,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted'
        ]);
    }

    public function changeStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->status = !$brand->status;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }
}
