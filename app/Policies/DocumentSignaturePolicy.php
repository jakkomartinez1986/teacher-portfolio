<?php

namespace App\Policies;

use App\Models\Document\DocumentSignature;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentSignaturePolicy
{
    use HandlesAuthorization;

    public function approve(User $user, DocumentSignature $signature)
    {
        // El usuario tiene el rol requerido y la firma está pendiente
        return $user->hasRole($signature->role->name) && 
               $signature->status === 'PENDING' &&
               ($signature->user_id === null || $signature->user_id === $user->id);
    }

    public function reject(User $user, DocumentSignature $signature)
    {
        // El usuario tiene el rol requerido y la firma está pendiente
        return $user->hasRole($signature->role->name) && 
               $signature->status === 'PENDING' &&
               ($signature->user_id === null || $signature->user_id === $user->id);
    }
// creado por el admin o el autor del documento
    public function sign(User $user, DocumentSignature $signature)
    {
        // El usuario tiene el rol requerido y la firma está pendiente
        return $user->hasRole($signature->role->name) && 
               $signature->status === 'PENDING' &&
               ($signature->user_id === null || $signature->user_id === $user->id);
    }

    public function view(User $user, DocumentSignature $signature)
    {
        // El usuario tiene el rol requerido y la firma está pendiente
        return $user->hasRole($signature->role->name) && 
               ($signature->user_id === null || $signature->user_id === $user->id);
    }
}