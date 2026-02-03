<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'nik' => $this->nik,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            
            // Organization
            'department' => [
                'id' => $this->department_id,
                'name' => $this->department?->name,
                'code' => $this->department?->code,
            ],
            'division' => [
                'id' => $this->division_id,
                'name' => $this->division?->name,
                'code' => $this->division?->code,
            ],
            'location' => [
                'id' => $this->location_id,
                'name' => $this->location?->name,
                'code' => $this->location?->code,
                'city' => $this->location?->city,
            ],
            'unit_usaha' => $this->unit_usaha,
            'position' => $this->position,
            'level' => $this->level,
            
            // Approval Chain
            'approval_chain' => [
                'supervisor' => $this->when($this->supervisor, [
                    'id' => $this->supervisor?->id,
                    'name' => $this->supervisor?->name,
                    'nik' => $this->supervisor?->nik,
                ]),
                'manager' => $this->when($this->manager, [
                    'id' => $this->manager?->id,
                    'name' => $this->manager?->name,
                    'nik' => $this->manager?->nik,
                ]),
                'senior_manager' => $this->when($this->seniorManager, [
                    'id' => $this->seniorManager?->id,
                    'name' => $this->seniorManager?->name,
                    'nik' => $this->seniorManager?->nik,
                ]),
                'director' => $this->when($this->director, [
                    'id' => $this->director?->id,
                    'name' => $this->director?->name,
                    'nik' => $this->director?->nik,
                ]),
                'owner' => $this->when($this->owner, [
                    'id' => $this->owner?->id,
                    'name' => $this->owner?->name,
                    'nik' => $this->owner?->nik,
                ]),
            ],
            
            // Leave & Attendance
            'leave_quota' => $this->leave_quota,
            'leave_group' => $this->leave_group,
            'max_hours' => (float) $this->max_hours,
            'can_attend_outside' => $this->can_attend_outside,
            
            // Status
            'status' => $this->status,
            'join_date' => $this->join_date?->format('Y-m-d'),
            'resign_date' => $this->resign_date?->format('Y-m-d'),
            
            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
