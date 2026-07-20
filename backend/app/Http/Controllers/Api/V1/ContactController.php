<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): JsonResponse
    {
        $msg = ContactMessage::create([
            'name'       => $request->string('name'),
            'email'      => $request->string('email'),
            'subject'    => $request->string('subject')->toString() ?: null,
            'message'    => $request->string('message'),
            'ip'         => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 240),
        ]);

        return response()->json([
            'message' => 'Thanks! I\'ll get back to you soon.',
            'id' => $msg->id,
        ], 201);
    }
}
