<?php

namespace App\Observers;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentObserver
{
    /**
     * Handle the Student "creating" event.
     */
    public function creating(Student $student): void
    {
        // Prevent observer from triggering during the sync process
        if ($student->relationLoaded('_syncing') && $student->_syncing) {
            return;
        }
    }

    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $this->syncToAllBranches($student, 'create');
    }

    /**
     * Handle the Student "updating" event.
     */
    public function updating(Student $student): void {}

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $this->syncToAllBranches($student, 'update');
    }

    /**
     * Handle the Student "deleting" event.
     */
    public function deleting(Student $student): void {}

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        $this->syncToAllBranches($student, 'delete');
    }

    /**
     * Sync student to all branches atomically.
     */
    private function syncToAllBranches(Student $student, string $action): void
    {
        $branches = ['branch_A', 'branch_B'];
        $studentData = $student->getAttributes();

        DB::transaction(function () use ($branches, $student, $studentData, $action) {
            foreach ($branches as $branch) {
                // For create action: skip branch_A since Student::create() already created it there
                if ($action === 'create' && $branch === 'branch_A') {
                    continue;
                }

                try {
                    if ($action === 'create') {
                        DB::connection($branch)->table('students')->insert($studentData);
                    } elseif ($action === 'update') {
                        DB::connection($branch)->table('students')
                            ->where('id', $student->id)
                            ->update($studentData);
                    } elseif ($action === 'delete') {
                        DB::connection($branch)->table('students')
                            ->where('id', $student->id)
                            ->delete();
                    }
                } catch (\Exception $e) {
                    // Transaction will rollback automatically
                    throw $e;
                }
            }
        });
    }
}
