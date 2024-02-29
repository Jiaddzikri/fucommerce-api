<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private Category $category)
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
    public function show(Request $request)
    {
        $filterParam = implode(" ", explode("-", $request->get("key")));
        $model = $this->category
            ->leftJoin("sub_categories_1", "categories.id", "=", "sub_categories_1.category_id")
            ->leftJoin("sub_categories_2", "sub_categories_1.id", "=", "sub_categories_2.sub_category_1_id")
            ->leftJoin("sub_categories_3", "sub_categories_3.sub_category_2_id",  "=", "sub_categories_2.id",)
            ->select([
                "categories.id AS category_id",
                "categories.name AS category_name",
                "sub_categories_1.id AS sub_category_1_id",
                "sub_categories_1.name AS sub_category_1_name",
                "sub_categories_2.id AS sub_category_2_id",
                "sub_categories_2.name AS sub_category_2_name",
                "sub_categories_3.id AS sub_category_3_id",
                "sub_categories_3.name AS sub_category_3_name"
            ])
            ->where(function ($query) use ($filterParam) {
                $query->where("categories.name", "like", "%" . $filterParam . "%")
                    ->orWhere("sub_categories_1.name", "like", "%" . $filterParam . "%")
                    ->orWhere("sub_categories_2.name", "like", "%" . $filterParam . "%")
                    ->orWhere("sub_categories_3.name", "like", "%" . $filterParam . "%");
            })
            ->get();

        $category = new CategoryResource($model);

        if (sizeof($category) == 0) {
            return response()->json([
                "message" => "Resource not found",
                "data" => null
            ], 404, ["Content-Type" => "application/json"]);
        }

        return $category;
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
}
