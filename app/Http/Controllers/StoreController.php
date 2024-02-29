<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private User $user)
    {
    }

    public function show($store_domain): JsonResponse
    {
        $query = $this->user->select(["store_name", "store_domain"])->where("store_domain", "=", $store_domain);

        if ($query->doesntExist()) {
            return response()->json([
                "message" => "Store not found",
                "data" => null
            ], 404, [
                "application/json"
            ]);
        }

        $data = $query->first();

        return response()->json([
            "message" => "Data found",
            "data" => [
                "store_name" => $data->store_name,
                "store_domain" => $data->store_domain
            ]
        ]);
    }

    public function storeHome(Request $request, $store_domain): void
    {
    }

    public function storeReviews(Request $request, $store_domain): void
    {
    }

    public function storeProducts(Request $request, $store_domain): JsonResponse|ProductsResource
    {
        try {
            $sort = $request->get("sort");
            $query =
                $this->user->select(["users.store_name", "users.store_domain", "products.name as product_name", "products.price", "products.slug", "product_images.main_image_path"])
                ->leftJoin("products", "users.id", "=", "products.user_id")
                ->leftJoin("product_images", "products.id", "=", "product_images.product_id")
                ->where("users.store_domain", "=", $store_domain);

            switch ($sort) {
                case "1":
                    $query->orderBy("products.price", "desc");
                    break;
                case "2":
                    $query->orderBy("products.price", "asc");
                    break;
                case "3":
                    $query->orderBy("products.created_at", "asc");
                    break;
                case "4":
                    $query->orderBy("prodcuts.created_at", "desc");
                    break;
                default:
                    break;
            }

            $getProducts = new ProductsResource($query->get());

            if ($getProducts->isEmpty()) {
                throw new Exception("Products is Empty", 404);
            }

            return $getProducts;
        } catch (Exception $error) {
            return response()->json([
                "message" => "Products not found",
                "data" => null
            ], $error->getCode(), ["Content-Type" => "application/json"]);
        }
    }
}
