<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Mail\MessageReceivedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductChat extends Component
{
    public int $productId = 0;
    public int $threadUserId = 0;

    #[Validate('required|string|max:1000')]
    public string $body = '';

    public function mount(int $productId, int $threadUserId): void
    {
        $this->productId    = $productId;
        $this->threadUserId = $threadUserId;
        $this->markRead();
    }

    public function sendMessage(): void
    {
        $this->validate();

        // Permitir: admin, comprador (thread_user) o dueño del producto (vendedor)
        $product = Product::with('user')->find($this->productId);
        $threadUser = User::find($this->threadUserId);
        
        $isBuyer  = Auth::id() === $this->threadUserId;
        $isSeller = $product && Auth::id() === $product->user_id;
        
        if (! Auth::user()->is_admin && ! $isBuyer && ! $isSeller) {
            abort(403);
        }

        $message = Message::create([
            'product_id'     => $this->productId,
            'thread_user_id' => $this->threadUserId,
            'sender_id'      => Auth::id(),
            'body'           => trim($this->body),
            'created_at'     => now(),
        ]);

        $this->body = '';
        
        // Antes de enviar el email, intentamos marcar como leído (si el otro está online en el chat)
        // Pero en Livewire, el "markRead" del destinatario ocurre en SU propia instancia del componente.
        // Por lo tanto, enviamos el email solo si el destinatario no ha leído los mensajes recientemente
        // o basándonos en si el mensaje sigue como unread_at null después de un pequeño intervalo (difícil en sync).
        
        // Mejor aproximación: Enviar email solo si el destinatario NO es el que acaba de enviar y NO tiene el chat abierto.
        // Dado que no tenemos presencia en tiempo real (WebSockets), usaremos la lógica de:
        // Si el destinatario es el vendedor y este no envió el mensaje, notificamos.
        // Para evitar spam si ambos están chateando, una técnica común es comprobar si el último mensaje del destinatario fue hace poco.
        $recipient = (Auth::id() === $product->user_id) ? $threadUser : $product->user;

        if ($recipient && $recipient->id !== Auth::id()) {
            // --- Start: Recipient online check ---
            $recipientIsOnline = false;

            // Check 1: Did the recipient send a message recently?
            $hasSentRecently = Message::where('product_id', $this->productId)
                ->where('thread_user_id', $this->threadUserId)
                ->where('sender_id', $recipient->id) // Messages SENT by recipient
                ->where('created_at', '>=', now()->subSeconds(30))
                ->exists();

            if ($hasSentRecently) {
                $recipientIsOnline = true;
            } else {
                // Check 2: Was the LATEST message in the thread marked as read by the recipient?
                // Fetch the latest message in the thread.
                $latestMessage = Message::where('product_id', $this->productId)
                    ->where('thread_user_id', $this->threadUserId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Check if the latest message exists, has a read_at timestamp, and it's recent.
                // The read_at is set by Auth::id() when markRead is called.
                if ($latestMessage && $latestMessage->read_at && $latestMessage->read_at >= now()->subSeconds(30)) {
                    $recipientIsOnline = true;
                }
            }
            // --- End: Recipient online check ---

            // If recipient is NOT online, send email.
            if (! $recipientIsOnline) {
                Mail::to($recipient->email)->send(new MessageReceivedMail($message, $recipient, Auth::user()));
            }
        }

        $this->body = '';
        $this->markRead();
    }

    /** Marca como leídos los mensajes que NO son míos. */
    private function markRead(): void
    {
        Message::where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function refreshMessages(): void
    {
        $this->markRead();
    }

    public function render()
    {
        $messages    = Message::with('sender')
            ->where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->orderBy('created_at')
            ->get();

        $product    = Product::with(['images', 'user'])->find($this->productId);
        $threadUser = User::find($this->threadUserId);

        return view('livewire.product-chat', compact('messages', 'product', 'threadUser'));
    }
}
