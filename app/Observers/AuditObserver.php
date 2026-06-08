<?php
namespace App\Observers;
use App\Models\AuditLog;

class AuditObserver
{
    public static function log(string $action, string $modelType, int $modelId, string $desc = ''): void
    {
        if (!auth()->check()) return;
        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $desc,
            'ip_address'  => request()->ip(),
        ]);
    }
}
