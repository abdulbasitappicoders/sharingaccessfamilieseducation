<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatListMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'message' => $this->message,
            'type' => $this->type,
            'to_user' => $this->toUser,
            'from_user' => $this->fromUser,
            'messages_files' => $this->messagesFiles,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
