<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Order;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::with([
    'buyer',
    'seller.store',
    'product',
    'messages',
])
->withCount('unreadMessages')
->where(function ($query) use ($user) {
    $query->where('buyer_id', $user->id)
          ->orWhere('seller_id', $user->id);
})
->latest()
->get();

        return Inertia::render(
            'Chat/Index',
            [
                'conversations' => $conversations
            ]
        );
    }

    public function start(Product $product)
    {
        $sellerId = $product->store->user_id;

        $conversation = Conversation::firstOrCreate(
            [
                'buyer_id' => auth()->id(),
                'seller_id' => $sellerId,
                'product_id' => $product->id,
            ]
        );

        return redirect()->route(
            'chat.show',
            $conversation
        );
    }

    public function startFromOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $orderItem = $order->items()->with('product.store')->first();

        if (! $orderItem?->product?->store) {
            return back()->with('error', 'Penjual produk ini sudah tidak tersedia.');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id' => auth()->id(),
            'seller_id' => $orderItem->product->store->user_id,
            'product_id' => $orderItem->product_id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    // Allow a seller to start a chat with a specific buyer for a product
    public function startWithBuyer(Product $product, $buyerId)
    {
        $sellerId = auth()->id();

        // ensure current user is the product owner
        if ($product->store->user_id !== $sellerId) {
            abort(403);
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'product_id' => $product->id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    // Redirect seller to the latest conversation for a given product (if exists)
    public function redirectToLatestConversation(Product $product)
    {
        $sellerId = auth()->id();

        $conversation = Conversation::where('product_id', $product->id)
            ->where('seller_id', $sellerId)
            ->orderByDesc('updated_at')
            ->first();

        if ($conversation) {
            return redirect()->route('chat.show', $conversation);
        }

        // fallback: open seller product detail page
        return redirect()->route('seller.products.show', $product);
    }

    // Redirect current user (seller or buyer) to their most recent conversation
    public function redirectToNewestConversation()
    {
        $userId = auth()->id();

        $conversation = Conversation::where(function ($q) use ($userId) {
            $q->where('buyer_id', $userId)
              ->orWhere('seller_id', $userId);
        })->orderByDesc('updated_at')->first();

        if ($conversation) {
            return redirect()->route('chat.show', $conversation);
        }

        // nothing found -> go to chat index
        return redirect()->route('chat.index');
    }

   public function show(
    Conversation $conversation
) {

    if (
        auth()->id() !== $conversation->buyer_id &&
        auth()->id() !== $conversation->seller_id
    ) {
        abort(403);
    }

    $conversation->load([
        'buyer',
        'seller.store',
        'product',
        'messages' => function ($query) {
            $query->with('sender')
                ->oldest();
        },
    ]);

    Message::where(
        'conversation_id',
        $conversation->id
    )
    ->where(
        'sender_id',
        '!=',
        auth()->id()
    )
    ->update([
        'is_read' => true,
    ]);

    return Inertia::render(
        'Chat/Show',
        [
            'conversation' => $conversation,
        ]
    );
}

    public function send(
    Request $request,
    Conversation $conversation
) {

    if (
        auth()->id() !== $conversation->buyer_id &&
        auth()->id() !== $conversation->seller_id
    ) {
        abort(403);
    }

    $request->validate([
        'message' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    $message = Message::create([
    'conversation_id' => $conversation->id,
    'sender_id' => auth()->id(),
    'message' => $request->message,
]);

    try {
        event(new MessageSent($message));
    } catch (\Throwable $e) {
        Log::warning('Chat broadcast failed, but message was still saved.', [
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'error' => $e->getMessage(),
        ]);
    }

    $receiverId =
        auth()->id() === $conversation->buyer_id
        ? $conversation->seller_id
        : $conversation->buyer_id;

    Notification::create([
        'user_id' => $receiverId,
        'title' => 'Pesan Baru',
        'message' =>
            auth()->user()->name .
            ' mengirim pesan mengenai produk ' .
            $conversation->product->name,
        'type' => 'chat',
    ]);

    return back()->with(
        'success',
        'Pesan berhasil dikirim'
    );
}
}