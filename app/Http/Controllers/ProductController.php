<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductsResource;
use App\Models\ProductImages;
use App\Models\Products;
use App\Response\FindSessionResponse;
use App\Services\SessionService;
use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class ProductController extends Controller
{
  public function __construct(private Products $products, private SessionService $sessionService, private ProductImages $productImages)
  {
  }

  private function validatingRole(?string $access_token = null): FindSessionResponse
  {
    $find = $this->sessionService->find($access_token);
    if ($find->role != "seller") {
      throw new Exception("Unauthorized", 401);
    }
    return $find;
  }

  /**
   * Display a listing of the resource.
   *
   * @return JsonResource
   */
  public function index()
  {
    $data = $this->products->leftJoin("users", "users.id", "=", "products.user_id")->leftJoin("product_images", "products.id", "=", "product_images.product_id")
      ->select(["users.store_domain", "products.id", "products.name", "products.slug", "products.price", "products.description", "products.created_at", "products.updated_at", "product_images.main_image_name", "product_images.main_image_path", "product_images.second_image_name", "product_images.second_image_path", "product_images.third_image_name", "product_images.third_image_path", "product_images.fourth_image_name", "product_images.fourth_image_path"])
      ->get();
    return new ProductsResource($data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(CreateProductRequest $request)
  {
    $validated = $request->validated();
    try {
      $validateRole = $this->validatingRole($request->header("Authorization") ?? "");
      DB::transaction(function () use ($validated, $validateRole) {
        $generateProductId = uniqid();
        $generateProductImageId = uniqid();
        $image1HashName = $validated["image_1"]->hashName();
        $image2HashName = $validated["image_2"]->hashName();
        $image3HashName = $validated["image_3"]->hashName();
        $image4HashName = $validated["image_4"]->hashName();

        $insertProduct = $this->products;
        $insertProduct->id = $generateProductId;
        $insertProduct->user_id = $validateRole->user_id ?? null;
        $insertProduct->category_id =  $validated["category"] ?? null;
        $insertProduct->sub_category_1_id = $validated["sub_category_1"] ?? null;
        $insertProduct->sub_category_2_id = $validated["sub_category_2"] ?? null;
        $insertProduct->sub_category_3_id = $validated["sub_category_3"] ?? null;
        $insertProduct->name = $validated["name"] ?? null;
        $insertProduct->slug = strtolower(preg_replace("/[^A-Za-z0-9\-]/", "-", $validated["name"])) ?? null;
        $insertProduct->price = (int) $validated["price"] ?? null;
        $insertProduct->description = $validated["description"] ?? null;
        $insertProduct->save();

        $insertProductImage = $this->productImages;
        $insertProductImage->id = $generateProductImageId;
        $insertProductImage->product_id = $generateProductId;
        $insertProductImage->main_image_name = $image1HashName;
        $insertProductImage->main_image_path = "storage/images/" . $image1HashName;
        $insertProductImage->second_image_name = $image2HashName;
        $insertProductImage->second_image_path = "storage/images/" . $image2HashName;
        $insertProductImage->third_image_name = $image3HashName;
        $insertProductImage->third_image_path = "storage/images/" .  $image3HashName;
        $insertProductImage->fourth_image_name = $image4HashName;
        $insertProductImage->fourth_image_path = "storage/images/" . $image4HashName;
        $insertProductImage->save();

        $validated["image_1"]->storePubliclyAs("images", $image1HashName, "public");
        $validated["image_2"]->storePubliclyAs("images", $image2HashName, "public");
        $validated["image_3"]->storePubliclyAs("images", $image3HashName, "public");
        $validated["image_4"]->storePubliclyAs("images", $image4HashName, "public");
      });

      return response()
        ->json([
          "messsage" => "Successfully create product",
          "created_at" =>  date("y-m-d h:m:s", time())
        ], 201, [
          "Content-Type" => "application/json"
        ]);
    } catch (Exception $error) {
      return response()
        ->json([
          "message" => $error->getMessage(),
          "data" => null
        ], (int) $error->getCode(), ["Content-Type" => "application/json"]);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $product = new ProductsResource(
      $this->products::join("product_images", "products.id", "=", "product_images.product_id")->select("products.id", "products.name", "products.price", "products.description", "products.created_at", "products.updated_at", "product_images.main_image_name", "product_images.main_image_path", "product_images.second_image_name", "product_images.second_image_path", "product_images.third_image_name", "product_images.third_image_path", "product_images.fourth_image_name", "product_images.fourth_image_path")
        ->where("products.id", "=", $id)
        ->get()
    );
    if (sizeof($product) == 0) {
      return response()->json([
        "message" => "Product doesn't exists",
        "data" => null,
      ], 404, ["Content-Type" => "application/json"]);
    }

    return $product;
  }

  public function search(Request $request): JsonResponse|ProductsResource
  {
    $keyParam = $request->get("key");
    $minPrice = $request->get("pmin", 0);
    $maxPrice = $request->get("pmax", 1_000_000_000);
    $filter = $request->get("filter");

    try {
      if (!isset($keyParam)) {
        throw new Exception("need key parameter", 400);
      }

      $query = $this->products::select("products.name as product_name", "products.price as price", "products.slug as slug",  "product_images.main_image_path", "users.store_domain", "users.store_name")
        ->leftJoin("users", "users.id", "=", "products.user_id")
        ->leftJoin("product_images", "products.id", "=", "product_images.product_id")
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('sub_categories_1', 'categories.id', '=', "sub_categories_1.category_id")
        ->leftJoin('sub_categories_2', 'sub_categories_2.sub_category_1_id', "=", "sub_categories_1.id")
        ->leftJoin('sub_categories_3', 'sub_categories_3.sub_category_2_id', "=", 'sub_categories_2.id')
        ->where(function ($query) use ($keyParam) {
          $query->whereRaw("products.name LIKE ?", ["%" . $keyParam . "%"])
            ->orWhereRaw("sub_categories_1.name LIKE ?", ['%' . $keyParam . '%'])
            ->orWhereRaw("sub_categories_2.name LIKE ?", ['%' . $keyParam . '%'])
            ->orWhereRaw("sub_categories_3.name LIKE ?", ['%' . $keyParam . '%']);
        })
        ->where(function ($query) use ($minPrice, $maxPrice) {
          $query->where('products.price', '>', $minPrice)
            ->where('products.price', '<', $maxPrice);
        })
        ->get();

      $products = new ProductsResource($query);

      if (sizeof($products) == 0) {
        throw new Exception("Not Found", 404);
      }

      return $products;
    } catch (Exception $error) {
      return response()->json([
        "message" => $keyParam,
        "data" => null
      ], $error->getCode(), [
        "Content-Type" => "application/json"
      ]);
    }
  }

  public function showProductBySlug($store, $slug): JsonResponse|ProductsResource
  {
    $query = $this->products->select(["products.id", "products.name", "products.slug", "products.price", "products.description", "products.created_at", "products.updated_at", "product_images.main_image_name", "product_images.main_image_path", "product_images.second_image_name", "product_images.second_image_path", "product_images.third_image_name", "product_images.third_image_path", "product_images.fourth_image_name", "product_images.fourth_image_path"])->leftJoin("users", "users.id", "=", "products.user_id")
      ->leftJoin("product_images", "products.id", "=", "product_images.product_id")
      ->where(function ($query) use ($store, $slug) {
        $query->where("users.store_domain", "=", $store)
          ->where("products.slug", "=", $slug);
      })->get();
    $product =  new ProductsResource($query);

    if (sizeof($product) == 0) {
      return response()->json([
        "message" => "Product not found",
        "data" =>  null
      ], 404, ["Content-Type" => "application/json"]);
    }

    return $product;
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
  }

  public function update(UpdateProductRequest $updateRequest, $id): JsonResponse
  {
    $validated = $updateRequest->validated();
    try {
      $this->validatingRole($updateRequest->header("Authorization") ?? "");
      $update = $this->products::find($id);
      if (is_null($update)) {
        throw new Exception("Product not found", 404);
      }

      DB::transaction(function () use ($validated, $update) {
        $update->category_id = $validated["category_id"] ?? $update->category;
        $update->sub_category_1_id = $validated["sub_category_1_id"] ?? $update->sub_category_1_id;
        $update->sub_category_2_id = $validated["sub_category_2_id"] ?? $update->sub_category_2_id;
        $update->sub_category_3_id = $validated["sub_category_3_id"] ?? $update->sub_category_3_id;
        $update->name = $validated["name"] ?? $update->name;
        $update->price = !is_null($validated["price"]) ? (int) $validated["price"] : $update->price;
        $update->description = $validated["description"] ?? $update->description;
        $update->update();
      });
      return response()->json([
        "message" => "Update Success",
      ], 201, ["Content-Type" => "application/json"]);
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
        "data" => null
      ], (int) $error->getCode(), ["Content-Type" => "application/json"]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    try {
      $this->validatingRole($request->header("Authorization"));

      $productImages = $this->productImages::where("product_id", "=", $id)->first();
      $product = $this->products::find($id);
      if (is_null($productImages) && is_null($product)) {
        throw new Exception("Product doent's exists", 404);
      }
      DB::transaction(function () use ($productImages, $product) {
        Storage::disk("public")->delete("/images/" . $productImages->main_image_name);
        Storage::disk("public")->delete("/images/" . $productImages->second_image_name);
        Storage::disk("public")->delete("/images/" . $productImages->third_image_name);
        Storage::disk("public")->delete("/images/" . $productImages->fourth_image_name);

        $productImages->delete();
        $product->delete();
      });
      return response()->json([
        "message" => "Success delete product",
        "deleted_at" => date("y-m-d h:m:s", time())
      ], 200, ["Content-Type" => "application/json"]);
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
      ], 400, ["Content-Type" => "application/json"]);
    }
  }
}
