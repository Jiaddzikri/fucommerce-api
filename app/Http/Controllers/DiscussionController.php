<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductDiscussionRequest;
use App\Http\Requests\WriteProductDiscussionReplyRequest;
use App\Http\Resources\ProductDiscussionResource;
use App\Http\Resources\ProductReplyResource;
use App\Models\DiscussionReply;
use App\Models\ProductDiscussion;
use App\Services\SessionServiceImplementation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller
{
    public function __construct(private ProductDiscussion $productDisscussion, private SessionServiceImplementation $sessionService, private DiscussionReply $discussionReply)
    {
    }

    public function showProductDiscussions($productSlug): JsonResponse|ResourceCollection
    {
        $query = $this->productDisscussion->select([
            "product_discussion.id as discussion_id",
            "products.id as product_id",
            "sender_discussion.id as sender_id",
            "sender_discussion.username as sender_username",
            "product_discussion.content as discussion_content",
            "product_discussion.created_at as created_at",
            "product_discussion.updated_at as updated_at"
        ])
            ->leftJoin("products", "products.id", "=", "product_discussion.product_id")
            ->leftJoin("users as sender_discussion", "product_discussion.sender_id", "=", "sender_discussion.id")
            ->where("products.slug", "=", $productSlug)
            ->orderBy("product_discussion.created_at", "asc")
            ->get();

        $discussions = new ProductDiscussionResource($query);

        if (sizeof($discussions) == 0) {
            return response()->json([
                "message" => "This Product Still not have discussion",
                "data" => null
            ]);
        }

        return $discussions;
    }

    public function showProductReplies($productSlug): JsonResponse|ResourceCollection
    {
        $query = $this->discussionReply->select([
            "discussion_replies.id as replies_id",
            "products.id as product_id",
            "discussion_replies.discussion_id as discussion_id",
            "sender_reply.id as sender_id",
            "sender_reply.username as sender_username",
            "receiver_reply.id as receiver_id",
            "receiver_reply.username as receiver_username",
            "discussion_replies.content as content",
            "discussion_replies.created_at as created_at",
            "discussion_replies.updated_at as updated_at"
        ])
            ->leftJoin("products", "discussion_replies.product_id", "=", "products.id")
            ->leftJoin("product_discussion", "product_discussion.id", "=", "discussion_replies.discussion_id")
            ->leftJoin("users as sender_reply", "discussion_replies.sender_id", "=", "sender_reply.id")
            ->leftJoin("users as receiver_reply", "discussion_replies.receiver_id", "=", "receiver_reply.id")
            ->where("products.slug", "=", $productSlug)
            ->orderBy("discussion_replies.created_at", "asc")
            ->get();

        $replies = new ProductReplyResource($query);

        if (sizeof($replies) == 0) {
            return response()->json([
                "message" => "resource not found",
                "data" => null
            ], 404, ["Content-Type" => "application/json"]);
        }
        return $replies;
    }

    public function writeProductDiscussion(ProductDiscussionRequest $request): JsonResponse
    {
        $token = $request->header("Authorization");
        try {
            $user = $this->sessionService->find($token);
            DB::transaction(function () use ($request, $user) {
                $productDiscussion = $this->productDisscussion;
                $productDiscussion->id = uniqid();
                $productDiscussion->sender_id = $user->user_id;
                $productDiscussion->receiver_id = $request->post("receiver_id", null);
                $productDiscussion->product_id = $request->post("product_id");
                $productDiscussion->content = $request->post("content");
                $productDiscussion->save();
            });
            return response()->json([
                "message" => "Success Send Message",
                "data" => null,
            ], 200, ["Content-Type" => "application/json"]);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage(),
                "data" => null
            ], 400, ["Content-Type" => "application/json"]);
        }
    }

    public function writeReplyDiscussion(WriteProductDiscussionReplyRequest $replyRequest): JsonResponse
    {
        $token = $replyRequest->header("Authorization");
        $validated =  $replyRequest->validated();
        try {
            $user = $this->sessionService->find($token);

            DB::transaction(function () use ($validated, $user) {
                $discussionReply = $this->discussionReply;
                $discussionReply->id = uniqid();
                $discussionReply->product_id = $validated["product_id"];
                $discussionReply->discussion_id = $validated["discussion_id"];
                $discussionReply->sender_id = $user->user_id;
                $discussionReply->receiver_id = $validated["receiver_id"];
                $discussionReply->content = $validated["content"];
                $discussionReply->save();
            });

            return response()->json([
                "message" => "Success Send Message",
                "data" => null,
            ], 200, ["Content-Type" => "application/json"]);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage(),
                "data" => null
            ], 400, ["Content-Type" => "application/json"]);
        }
    }
}
