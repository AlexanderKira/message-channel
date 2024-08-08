<?php

namespace App\Http\Builders\Filter;

use App\Enums\MessageTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MessageFilter extends QueryFilter
{
    public function date(string $update): void
    {
        preg_match('/\[(.*),(.*)\]/', $update, $matches);
        $from = Carbon::parse(trim($matches[1]));
        $to = Carbon::parse(trim($matches[2]));

        $this->builder->where('updated_at', [$from, $to]);
    }

    public function type(string $type): void
    {
        $user = Auth::user();

        $this->builder->where('type', $type);

        if($type === MessageTypeEnum::PRIVATE->value && $user){
            $this->builder->where('recipient_id', $user->id)->where('sender_id', $user->id);
        }
    }
}
//{
//    "filter": {
//    "date": {
//        "from":  "2024-08-06 10:13:16",
//					"to": "2024-08-06 10:13:16"
//				},
//		 "type": "private"
//    }
//}
