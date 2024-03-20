<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductCartRequest;
use App\Http\Resources\CartResource;
use App\Models\CartModel;
use App\Services\SessionServiceImplementation;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
  public function __construct(private SessionServiceImplementation $session, private CartModel $cartModel)
  {
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(CreateProductCartRequest $request)
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CreateProductCartRequest $request)
  {
    $validatedRequest = $request->validated();

    try {
      $session = $this->session->find($request->header("Authorization"));

      DB::transaction(function () use ($validatedRequest, $session) {
        $cartModel = $this->cartModel;

        $query =  $cartModel->where(function (Builder $query) use ($session, $validatedRequest) {
          $query->where("session_id", "=", $session->session_id)
            ->where("product_id", "=", $validatedRequest["product_id"]);
        });
        $data = $query->first();

        if ($query->doesntExist()) {
          $cartModel->id = uniqid();
          $cartModel->product_id = $validatedRequest["product_id"];
          $cartModel->session_id = $session->session_id;
          $cartModel->quantity = 1;
          $cartModel->save();
        } else {
          $query->update([
            "quantity" => $data->quantity + 1
          ]);
        }
      });

      return response()->json([
        "message" => "Success",
        "data" => null
      ], 201, [
        "Content-Type" => "application/json"
      ]);
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
        "data" => null
      ]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    $token = $request->header("Authorization");

    try {
      $session = $this->session->find($token);
      $query = $this->cartModel->select([
        "carts.id as cart_id",
        "products.id as product_id",
        "products.name as product_name",
        "products.price as product_price",
        "products.slug as product_slug",
        "carts.quantity as quantity",
        "sessions.id as session_id",
        "sessions.user_id as user_id",
        "users.store_domain as store_domain",
        "product_images.main_image_path as main_image"
      ])->leftJoin("sessions", "carts.session_id", "=", "sessions.id")
        ->leftJoin("products", "carts.product_id", "=", "products.id")
        ->leftJoin("users", "products.user_id", "=", "users.id")
        ->leftJoin("product_images", "products.id", "=", "product_images.product_id")
        ->where("session_id", "=", $session->session_id)
        ->get();

      $resources = new CartResource($query);

      if ($resources->isEmpty()) {
        throw new Exception("Not Found", 404);
      }
      return $resources;
    } catch (Exception $error) {
      return response()->json([
        "message" => $error->getMessage(),
        "data" => null
      ], $error->getCode(), [
        "Content-Type" => "application/json"
      ]);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $token = $request->header("Authorization");

    try {
      $this->session->find($token);
      $find = $this->cartModel->find($id);

      DB::transaction(function () use ($find) {
        $find->delete();
      });
      return response()->json([
        "message" => "Success",
        "data" => null
      ], 200, [
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
}
