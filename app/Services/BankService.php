<?php

namespace App\Services;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BankService
{
    /**
     * Get paginated list of bank accounts
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing accounts and total count
     */
    public function getList(
        array $search = [],
        string $sortfield = 'label',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = BankAccount::query()
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (!empty($search['number'])) {
            $query->where('number', 'like', '%' . $search['number'] . '%');
        }

        if (isset($search['clos'])) {
            $query->where('clos', $search['clos']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $accounts = $query->get();

        return [
            'accounts' => $accounts,
            'total' => $total,
        ];
    }

    /**
     * Get a single bank account by ID
     *
     * @param int $id Account ID
     * @return BankAccount|null
     */
    public function getById(int $id): ?BankAccount
    {
        return BankAccount::find($id);
    }

    /**
     * Get a bank account by reference
     *
     * @param string $ref Account reference
     * @return BankAccount|null
     */
    public function getByRef(string $ref): ?BankAccount
    {
        return BankAccount::where('ref', $ref)->first();
    }

    /**
     * Create a new bank account
     *
     * @param array $data Account data
     * @return BankAccount
     */
    public function create(array $data): BankAccount
    {
        return BankAccount::create($data);
    }

    /**
     * Update an existing bank account
     *
     * @param int $id Account ID
     * @param array $data Updated data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $account = BankAccount::findOrFail($id);
        return $account->update($data);
    }

    /**
     * Delete a bank account
     *
     * @param int $id Account ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $account = BankAccount::findOrFail($id);
        return $account->delete();
    }

    /**
     * Close a bank account
     *
     * @param int $id Account ID
     * @return bool
     */
    public function close(int $id): bool
    {
        $account = BankAccount::findOrFail($id);
        return $account->update(['clos' => 1]);
    }

    /**
     * Reopen a bank account
     *
     * @param int $id Account ID
     * @return bool
     */
    public function reopen(int $id): bool
    {
        $account = BankAccount::findOrFail($id);
        return $account->update(['clos' => 0]);
    }

    /**
     * Get account balance
     *
     * @param int $id Account ID
     * @return float
     */
    public function getBalance(int $id): float
    {
        $account = BankAccount::findOrFail($id);
        
        // Calculate balance from bank transactions
        $balance = DB::table('llx_bank')
            ->where('fk_account', $id)
            ->sum('amount');
        
        return (float) $balance;
    }
}
