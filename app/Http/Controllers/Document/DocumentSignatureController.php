<?php

namespace App\Http\Controllers\Document;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Document\DocumentSignature;

class DocumentSignatureController extends Controller
{
    public function approve(DocumentSignature $signature, Request $request)
    {
        $this->authorize('approve', $signature);

        $validated = $request->validate([
            'comments' => 'nullable|string'
        ]);

        $signature->update([
            'status' => 'APPROVED',
            'signed_at' => now(),
            'comments' => $validated['comments'],
            'user_id' => Auth::id()
        ]);

        $document = $signature->document;

        // Verificar si todas las firmas están aprobadas
        $this->checkDocumentStatus($document);

        return redirect()->back()->with('success', 'Documento aprobado exitosamente.');
    }

    public function reject(DocumentSignature $signature, Request $request)
    {
        $this->authorize('approve', $signature);

        $validated = $request->validate([
            'comments' => 'required|string'
        ]);

        $signature->update([
            'status' => 'REJECTED',
            'signed_at' => now(),
            'comments' => $validated['comments'],
            'user_id' => Auth::id()
        ]);

        $document = $signature->document;
        $document->update(['status' => 'REJECTED']);

        return redirect()->back()->with('success', 'Documento rechazado.');
    }

    protected function checkDocumentStatus($document)
    {
        $pendingSignatures = $document->signatures()
            ->where('status', 'PENDING')
            ->count();

        if ($pendingSignatures === 0) {
            $document->update(['status' => 'APPROVED']);
        } else {
            // Notificar al siguiente aprobador
            $nextSignature = $document->signatures()
                ->where('status', 'PENDING')
                ->orderBy('created_at')
                ->first();

            if ($nextSignature) {
                // Implementar notificación (se verá más adelante con Livewire)
            }
        }
    }
}
