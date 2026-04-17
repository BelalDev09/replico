<?php

namespace App\Http\Controllers\Web\backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SubCategory::with('category')->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bulk_check', function ($row) {
                    return '
                        <input type="checkbox"
                            class="form-check-input select_data"
                            value="' . $row->id . '"
                            onclick="select_single_item(' . $row->id . ')">
                    ';
                })

                ->addColumn('category', function ($row) {
                    return $row->category?->name ?? '-';
                })

                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset($row->image) . '" width="60" height="60" style="object-fit:cover;">'
                        : 'No Image';
                })

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
                        <a href="' . route('admin.sub-categories.edit', $row->id) . '"
                           class="btn btn-sm btn-primary">Edit</a>

                        <button onclick="showDeleteAlert(' . $row->id . ')"
                            class="btn btn-sm btn-danger">Delete</button>
                    ';
                })

                ->rawColumns(['bulk_check', 'image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.subcategories.index');
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('backend.layout.subcategories.create', compact('categories'));
    }

    public function store(SubCategoryRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'sub-categories',
                Str::slug($request->name) . '_' . time()
            );
        }

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => 1,
        ]);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'SubCategory created successfully');
    }

    public function edit($id)
    {
        $data = SubCategory::findOrFail($id);
        $categories = Category::where('status', 1)->get();

        return view('backend.layout.subcategories.edit', compact('data', 'categories'));
    }

    public function update(SubCategoryRequest $request, $id)
    {
        $sub = SubCategory::findOrFail($id);

        $imagePath = $sub->image;

        if ($request->hasFile('image')) {

            if ($imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'sub-categories',
                Str::slug($request->name) . '_' . time()
            );
        }

        $sub->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'SubCategory updated successfully');
    }

    public function destroy($id)
    {
        $sub = SubCategory::findOrFail($id);

        if ($sub->image && file_exists(public_path($sub->image))) {
            unlink(public_path($sub->image));
        }

        $sub->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    public function changeStatus($id)
    {
        $sub = SubCategory::findOrFail($id);

        $sub->status = !$sub->status;
        $sub->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        $subs = SubCategory::whereIn('id', $ids)->get();

        foreach ($subs as $sub) {

            if ($sub->image && file_exists(public_path($sub->image))) {
                unlink(public_path($sub->image));
            }

            $sub->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk deleted successfully'
        ]);
    }
}
