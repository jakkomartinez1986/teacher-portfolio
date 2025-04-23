<?php

namespace App\Policies;

use App\Models\Document\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view-any-document');
    }

    public function view(User $user, Document $document)
    {
        return $user->can('view-document') || 
               $document->creator_id === $user->id || 
               $document->authors->contains($user->id) ||
               $document->signatures()->where('user_id', $user->id)->exists() ||
               $user->hasRole('admin');
        
    }

    public function create(User $user)
    {
        return $user->can('create-document');
    }

    public function update(User $user, Document $document)
    {
        return $document->status === 'DRAFT' && 
               ($user->can('update-document') || 
               $document->creator_id === $user->id || 
               $document->authors->contains($user->id) ||
               $user->hasRole('admin'));
    }

    public function delete(User $user, Document $document)
    {
        return $user->can('delete-document') || 
               ($document->creator_id === $user->id && $document->status === 'DRAFT') ||
               $user->hasRole('admin');
    }

    public function submitForReview(User $user, Document $document)
    {
        return $document->status === 'DRAFT' && 
               ($document->creator_id === $user->id || 
               $document->authors->contains($user->id) ||
               $user->hasRole('admin'));
    }
// creado por el admin o el autor del documento
    public function approve(User $user, Document $document)
    {
        return $document->status === 'PENDING' && 
               $document->signatures()->where('user_id', $user->id)->exists() &&
               ($user->can('approve-document') || 
               $document->creator_id === $user->id || 
               $document->authors->contains($user->id) ||
               $user->hasRole('admin'));
    }

    public function reject(User $user, Document $document)
    {
        return $document->status === 'PENDING' && 
               $document->signatures()->where('user_id', $user->id)->exists() &&
               ($user->can('reject-document') || 
               $document->creator_id === $user->id || 
               $document->authors->contains($user->id) ||
               $user->hasRole('admin'));
    }
}