<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dar_number' => $this->dar_number,
            
            // Employee
            'employee' => [
                'id' => $this->employee_id,
                'nik' => $this->employee?->nik,
                'name' => $this->employee?->name,
                'position' => $this->employee?->position,
            ],
            
            // DAR Content
            'dar_date' => $this->dar_date?->format('Y-m-d'),
            'activity' => $this->activity,
            'result' => $this->result,
            'plan' => $this->plan,
            'tag' => $this->tag,
            
            // Approval Status
            'status' => $this->status,
            'supervisor_status' => $this->supervisor_status,
            'manager_status' => $this->manager_status,
            'senior_manager_status' => $this->senior_manager_status,
            'director_status' => $this->director_status,
            'owner_status' => $this->owner_status,
            
            // Approval Details
            'supervisor_notes' => $this->supervisor_notes,
            'manager_notes' => $this->manager_notes,
            'senior_manager_notes' => $this->senior_manager_notes,
            'director_notes' => $this->director_notes,
            'owner_notes' => $this->owner_notes,
            
            // Approval Dates
            'supervisor_approved_at' => $this->supervisor_approved_at?->toISOString(),
            'manager_approved_at' => $this->manager_approved_at?->toISOString(),
            'senior_manager_approved_at' => $this->senior_manager_approved_at?->toISOString(),
            'director_approved_at' => $this->director_approved_at?->toISOString(),
            'owner_approved_at' => $this->owner_approved_at?->toISOString(),
            
            // Approvers
            'approvers' => [
                'supervisor' => $this->when($this->employee?->supervisor, [
                    'id' => $this->employee?->supervisor?->id,
                    'name' => $this->employee?->supervisor?->name,
                    'nik' => $this->employee?->supervisor?->nik,
                ]),
                'manager' => $this->when($this->employee?->manager, [
                    'id' => $this->employee?->manager?->id,
                    'name' => $this->employee?->manager?->name,
                    'nik' => $this->employee?->manager?->nik,
                ]),
                'senior_manager' => $this->when($this->employee?->seniorManager, [
                    'id' => $this->employee?->seniorManager?->id,
                    'name' => $this->employee?->seniorManager?->name,
                    'nik' => $this->employee?->seniorManager?->nik,
                ]),
                'director' => $this->when($this->employee?->director, [
                    'id' => $this->employee?->director?->id,
                    'name' => $this->employee?->director?->name,
                    'nik' => $this->employee?->director?->nik,
                ]),
                'owner' => $this->when($this->employee?->owner, [
                    'id' => $this->employee?->owner?->id,
                    'name' => $this->employee?->owner?->name,
                    'nik' => $this->employee?->owner?->nik,
                ]),
            ],
            
            // Attachments
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'filename' => $attachment->original_filename,
                        'url' => $attachment->url,
                        'size' => $attachment->size_in_kb . ' KB',
                        'type' => $attachment->mime_type,
                    ];
                });
            }),
            
            // Metadata
            'submitted_at' => $this->submitted_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
