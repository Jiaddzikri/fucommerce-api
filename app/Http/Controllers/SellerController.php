<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSellerRequest;
use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Response\FindSessionResponse;
use App\Services\SessionServiceImplementation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function __construct(private User $user, private SessionServiceImplementation $sessionService)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */

    public function store(CreateSellerRequest $request)
    {
        $validated = (array) $request->validated();
        try {
            $session = $this->validateRole($request->header("Authorization"));
            $user = $this->user::find($session->user_id);
            DB::transaction(function () use ($validated, $user) {
                $user->role = "seller";
                $user->phone_number = $validated["phone_number"];
                $user->store_name = $validated["store_name"];
                $user->store_domain = $validated["store_domain"];
                $user->update();
            });

            $this->sessionService->delete($session->accessToken);

            $request = App::make(CreateSessionRequest::class);
            $request->role = "seller";
            $request->user_id = $session->user_id;

            $create = $this->sessionService->create($request);

            return response()->json([
                "message" => "Congratulation!, you have success to open your store.",
                "data" => [
                    "access_token" => $create->accessToken,
                    "expire_at" => $create->expire
                ],
                "created_at" => date("y-m-d h:m:s", time())
            ], 201, ["Content-Type" => "application/json"]);
        } catch (Exception $error) {
            return \response()->json([
                "message" => $error->getMessage(),
            ], (int) $error->getCode(), ["Content-Type" => "application/json"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    private function validateRole(?string $accesToken = null): FindSessionResponse
    {
        $find = $this->sessionService->find($accesToken);

        if ($find->role != "customer") {
            throw new Exception("You have no authorization to do this operation!", 401);
        }
        return $find;
    }
}
