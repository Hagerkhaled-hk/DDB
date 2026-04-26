<?php

namespace App\Observers;

use App\Models\Record;
use Illuminate\Support\Facades\DB;

class RecordObserver
{
    /**
     * Handle the Record "created" event.
     */
    public function created(Record $record): void
    {
        $this->syncToAllBranches($record, 'create');
    }

    /**
     * Handle the Record "updated" event.
     */
    public function updated(Record $record): void
    {
        $this->syncToAllBranches($record, 'update');
    }

    /**
     * Handle the Record "deleted" event.
     */
    public function deleted(Record $record): void
    {
        $this->syncToAllBranches($record, 'delete');
    }

    /**
     * Sync record to all branches atomically.
     */
    private function syncToAllBranches(Record $record, string $action): void
    {
        $branches = ['branch_A', 'branch_B'];
        $recordData = $record->getAttributes();

        DB::transaction(function () use ($branches, $record, $recordData, $action) {
            foreach ($branches as $branch) {
                try {
                    if ($action === 'create') {
                        DB::connection($branch)->table('records')->insert($recordData);
                    } elseif ($action === 'update') {
                        DB::connection($branch)->table('records')
                            ->where('id', $record->id)
                            ->update($recordData);
                    } elseif ($action === 'delete') {
                        DB::connection($branch)->table('records')
                            ->where('id', $record->id)
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
