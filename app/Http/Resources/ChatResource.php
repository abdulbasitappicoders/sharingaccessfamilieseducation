<?php

namespace App\Http\Resources;

use App\Models\ChatListMessage;
use App\Models\SupportMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $last_massage = null;
        if ($this->faq_category_id != null) {
//            $last_massage = SupportMessage::where('chat_list_id', $this->faq_category_id)->orderByDesc('id')->first();
            $last_massage = $this->supportMessages->last();
        } else {
            $last_massage = $this->messages->last();
        }

        return [
            'id' => $this->id,
            'to' => $this->toUser,
            'from' => $this->fromUser,
            'created_at' => $this->created_at,
            'category' => $this->category,
            'last_message' => $last_massage,
        ];
    }
}
