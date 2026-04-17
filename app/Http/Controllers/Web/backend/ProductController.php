<?php

namespace App\Http\Controllers\Web\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\SubCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'subCategory', 'brand']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $this->productService->getThumbnailHtml($row))
                ->addColumn('category', fn($row) => $row->category?->name ?? '-')
                ->addColumn('sub_category', fn($row) => $row->subCategory?->name ?? '-')
                ->addColumn('brand', fn($row) => $row->brand?->name ?? '-')
                ->addColumn('price', fn($row) => '৳ ' . number_format($row->price, 2))
                ->addColumn('stock', fn($row) => $this->productService->getStockHtml($row))
                ->addColumn('status', fn($row) => $this->productService->getStatusHtml($row))
                ->addColumn('action', fn($row) => $this->productService->getActionHtml($row))
                ->addColumn('bulk_check', function ($row) {
                    return '<input type="checkbox" class="select_data" value="' . $row->id . '" onclick="select_single_item(' . $row->id . ')">';
                })
                ->rawColumns(['image', 'stock', 'status', 'action', 'bulk_check'])
                ->make(true);
        }

        return view('backend.layout.products.index');
    }

    public function create()
    {
        $data = $this->productService->getDataForCreate();
        return view('backend.layout.products.create', $data);
    }

    public function store(ProductRequest $request)
    {
        try {
            $this->productService->store($request);
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $data = $this->productService->getDataForEdit($product);
        return view('backend.layout.products.edit', $data);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->productService->update($request, $product);
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function changeStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)
            ->where('status', true)
            ->get(['id', 'name']);

        return response()->json($subCategories);
    }
    public function bulkDelete(Request $request)
    {
        Product::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected products deleted successfully'
        ]);
    }
}
