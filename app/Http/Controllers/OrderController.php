<?php

namespace App\Http\Controllers;

use App\Http\Requests\DirectBuyRequest;
use App\Http\Resources\DirectBuyResource;
use App\Models\DirectBuy;
use App\Services\SessionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  public function __construct(private SessionService $session, private DirectBuy $directBuy)
  {
  }

  public function directBuy(DirectBuyRequest $request): JsonResponse
  {
    $token = $request->header("Authorization");
    $validated = $request->validated();
    $directBuyModel = $this->directBuy;

    try {
      $session = $this->session->find($token);

      DB::transaction(function () use ($validated, $directBuyModel, $session) {
        $find = $directBuyModel->where("session_id", "=", $session->session_id);

        if ($find->exists()) {
          $find->update([
            "product_id" => $validated["product_id"],
            "note" => $validated["note"],
            "quantity" => (int) $validated["quantity"]
          ]);
        } else {
          $directBuyModel->id = uniqid();
          $directBuyModel->product_id = $validated["product_id"];
          $directBuyModel->session_id = $session->session_id;
          $directBuyModel->note = $validated["note"];
          $directBuyModel->quantity = (int) $validated["quantity"];
          $directBuyModel->save();
        }
      });
      return response()->json([
        "message" => "success",
        "data" => null
      ], 201, [
        "Content-Type" => "application/json"
      ]);
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
        "data" => null
      ], $error->getCode(), [
        "Content-Type" => "application/json"
      ]);
    }
  }

  public function showDirectBuyData(Request $request): JsonResponse|ResourceCollection
  {
    $token = $request->header("token");

    try {
      $sessionData = $this->session->find($token);

      $findProduct = $this->directBuy->select([
        "direct_buy.id as direct_buy_id",
        "products.id as product_id",
        "products.name as product_name",
        "products.price as product_price",
        "products.slug as product_slug",
        "product_images.main_image_path as main_image_path",
        "users.store_domain as seller_domain",
        "users.store_name as store_name"
      ])->leftJoin("products", "direct_buy.product_id", "=", "products.id")
        ->leftJoin("product_images", "products.id", "=", "product_images.product_id")
        ->leftJoin("users", "users.id", "=", "products.user_id")
        ->where("direct_buy.session_id", "=", $sessionData->session_id)
        ->get();


      if ($findProduct->isEmpty()) {
        throw new Exception("not found", 404);
      }
      return new DirectBuyResource($findProduct);
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
        "data" => null
      ], 500, [
        "Content-Type" => "application/json"
      ]);
    }
  }
}
