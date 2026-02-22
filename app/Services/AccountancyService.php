<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AccountancyService
{
    /**
     * Get accounting entries
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing entries and total count
     */
    public function getEntries(
        array $search = [],
        string $sortfield = 'doc_date',
        string $sortorder = 'DESC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = DB::table('llx_accounting_bookkeeping')
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['doc_ref'])) {
            $query->where('doc_ref', 'like', '%' . $search['doc_ref'] . '%');
        }

        if (!empty($search['numero_compte'])) {
            $query->where('numero_compte', 'like', '%' . $search['numero_compte'] . '%');
        }

        if (!empty($search['code_journal'])) {
            $query->where('code_journal', $search['code_journal']);
        }

        if (!empty($search['date_start']) && !empty($search['date_end'])) {
            $query->whereBetween('doc_date', [$search['date_start'], $search['date_end']]);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $entries = $query->get();

        return [
            'entries' => $entries,
            'total' => $total,
        ];
    }

    /**
     * Get journal codes
     *
     * @param int $entity Entity ID
     * @return array
     */
    public function getJournalCodes(int $entity = 1): array
    {
        return DB::table('llx_accounting_journal')
            ->where('entity', $entity)
            ->where('active', 1)
            ->orderBy('code', 'ASC')
            ->get()
            ->toArray();
    }

    /**
     * Get account balance
     *
     * @param string $accountNumber Account number
     * @param int $entity Entity ID
     * @return float
     */
    public function getAccountBalance(string $accountNumber, int $entity = 1): float
    {
        $debit = DB::table('llx_accounting_bookkeeping')
            ->where('numero_compte', $accountNumber)
            ->where('entity', $entity)
            ->sum('debit');

        $credit = DB::table('llx_accounting_bookkeeping')
            ->where('numero_compte', $accountNumber)
            ->where('entity', $entity)
            ->sum('credit');

        return (float) ($debit - $credit);
    }

    /**
     * Get general ledger for a period
     *
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @param int $entity Entity ID
     * @return array
     */
    public function getGeneralLedger(string $startDate, string $endDate, int $entity = 1): array
    {
        return DB::table('llx_accounting_bookkeeping')
            ->where('entity', $entity)
            ->whereBetween('doc_date', [$startDate, $endDate])
            ->orderBy('doc_date', 'ASC')
            ->orderBy('numero_compte', 'ASC')
            ->get()
            ->toArray();
    }

    /**
     * Get trial balance
     *
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @param int $entity Entity ID
     * @return array
     */
    public function getTrialBalance(string $startDate, string $endDate, int $entity = 1): array
    {
        return DB::table('llx_accounting_bookkeeping')
            ->select([
                'numero_compte',
                'label_compte',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit'),
                DB::raw('SUM(debit - credit) as balance')
            ])
            ->where('entity', $entity)
            ->whereBetween('doc_date', [$startDate, $endDate])
            ->groupBy('numero_compte', 'label_compte')
            ->orderBy('numero_compte', 'ASC')
            ->get()
            ->toArray();
    }

    /**
     * Create an accounting entry
     *
     * @param array $data Entry data
     * @return int Entry ID
     */
    public function createEntry(array $data): int
    {
        return DB::table('llx_accounting_bookkeeping')->insertGetId($data);
    }

    /**
     * Delete accounting entries
     *
     * @param array $ids Array of entry IDs
     * @return int Number of deleted entries
     */
    public function deleteEntries(array $ids): int
    {
        return DB::table('llx_accounting_bookkeeping')
            ->whereIn('rowid', $ids)
            ->delete();
    }

    /**
     * Export accounting data
     *
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @param string $format Export format (csv, fec, etc.)
     * @param int $entity Entity ID
     * @return array
     */
    public function exportData(
        string $startDate,
        string $endDate,
        string $format = 'csv',
        int $entity = 1
    ): array {
        $entries = DB::table('llx_accounting_bookkeeping')
            ->where('entity', $entity)
            ->whereBetween('doc_date', [$startDate, $endDate])
            ->orderBy('doc_date', 'ASC')
            ->orderBy('piece_num', 'ASC')
            ->get()
            ->toArray();

        return [
            'format' => $format,
            'entries' => $entries,
        ];
    }
}
