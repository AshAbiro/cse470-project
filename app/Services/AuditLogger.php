<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Record an audit log entry.
     *
     * @param  string  $action
     * @param  \Illuminate\Database\Eloquent\Model|array|string|null  $subject
     * @param  array  $metadata
     */
    public static function log(string $action, $subject = null, array $metadata = []): void
    {
        $entityType = null;
        $entityId = null;

        if ($subject instanceof Model) {
            $entityType = get_class($subject);
            $entityId = $subject->getKey();
        } elseif (is_array($subject)) {
            $metadata = array_merge($metadata, $subject);
        } elseif (is_string($subject)) {
            $metadata['subject'] = $subject;
        }

        $user = Auth::user();

        AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
