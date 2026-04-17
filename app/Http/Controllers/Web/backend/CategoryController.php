<?php

namespace App\Http\Controllers\Web\backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::query();

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

                ->addColumn('name', fn($row) => $row->name)
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset($row->image) . '" width="60" height="60" style="object-fit:cover;">';
                    }
                    return '<span>No Image</span>';
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
    <a href="' . route('admin.categories.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
    <button onclick="showDeleteAlert(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>
    ';
                })

                ->rawColumns(['bulk_check', 'image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.categories.index');
    }

    public function create()
    {
        return view('backend.layout.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'categories',
                Str::slug($request->name) . '_' . time()
            );
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => true,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view('backend.layout.categories.edit', compact('data'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $imagePath = $category->image;

        if ($request->hasFile('image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'categories',
                Str::slug($request->name) . '_' . time()
            );
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function changeStatus($id)
    {
        $category = Category::findOrFail($id);

        $category->status = !$category->status;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $categories = Category::whereIn('id', $ids)->get();

        foreach ($categories as $cat) {
            if ($cat->image && file_exists(public_path($cat->image))) {
                unlink(public_path($cat->image));
            }
            $cat->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Selected categories deleted'
        ]);
    }
}
