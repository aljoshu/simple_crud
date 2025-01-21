<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    public function index()
    {
        // $products = Product::with(['status', 'category']);
        // $products = Product::all();
        $products = Product::where('status_id', 1)->with(['category'])->get();
        $categories = Category::all();
        $status = Status::all();

        return view('crud', compact('products', 'categories', 'status'));
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_produk' => 'nullable|integer|unique:products,id_produk',
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0|max:9999999.99',
                'kategori_id' => 'required|integer|exists:categories,id_kategori',
                'status_id' => 'required|integer|exists:status,id_status',
            ]);

            Product::create($validatedData);

            return redirect()->back()->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0|max:9999999.99',
                'kategori_id' => 'required|integer|exists:categories,id_kategori',
                'status_id' => 'required|integer|exists:status,id_status',
            ]);

            $product = Product::findOrFail($request->edit_product_id);
            $product->update($validatedData);

            return redirect()->back()->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $product = Product::findOrFail($request->input('id_produk'));
            $product->delete();

            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function getAllProducts()
    {
        try {
            $now = Carbon::now()->setTimeZone('Asia/Jakarta');

            $username = 'tesprogrammer210125C09';   // This will change daily
            $formattedDate = $now->format('d-m-y');

            $rawPassword = "bisacoding-" . $formattedDate;
            $password = md5($rawPassword);

            $url = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';

            $formData = [
                'username' => $username,
                'password' => $password,
            ];

            $response = Http::asForm()->post($url, $formData);

            // Log::info($response);
            // dd($response);

            if ($response->successful()) {
                return redirect('/crud')->with('success', 'API call successful: ' . json_encode($response->json()));
            } else {
                return redirect('/crud')->with('error', 'API call failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect('/crud')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function importProductsFromJson()
    {
        // Load and decode the JSON file
        $filePath = base_path('products.json');
        $jsonData = json_decode(file_get_contents($filePath), true);

        if (!$jsonData) {
            return response()->json(['message' => 'Invalid JSON file'], 400);
        }

        DB::beginTransaction();

        try {
            foreach ($jsonData as $data) {
                // Validate the data
                $validator = Validator::make($data, [
                    'id_produk' => 'required|integer|unique:products,id_produk',
                    'nama_produk' => 'required|string|max:255',
                    'kategori' => 'required|string|max:255',
                    'harga' => 'required|numeric',
                    'status' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                // Check and create kategori if it doesn't exist
                $category = Category::firstOrCreate(
                    ['nama_kategori' => $data['kategori']],
                    ['id_kategori' => Category::max('id_kategori') + 1]
                );

                // Check and create status if it doesn't exist
                $status = Status::firstOrCreate(
                    ['nama_status' => $data['status']],
                    ['id_status' => Status::max('id_status') + 1]
                );

                // Insert product into the database
                Product::create([
                    'id_produk' => $data['id_produk'],
                    'nama_produk' => $data['nama_produk'],
                    'harga' => $data['harga'],
                    'kategori_id' => $category->id_kategori,
                    'status_id' => $status->id_status,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Products imported successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
