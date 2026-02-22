<?php

namespace App\Services;

use App\Models\SupplierProposal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SupplierProposalService
{
    /**
     * Get paginated list of supplier proposals
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing proposals and total count
     */
    public function getList(
        array $search = [],
        string $sortfield = 'date_valid',
        string $sortorder = 'DESC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = SupplierProposal::query()
            ->select([
                'llx_supplier_proposal.*',
                'llx_societe.nom as societe_nom'
            ])
            ->leftJoin('llx_societe', 'llx_supplier_proposal.fk_soc', '=', 'llx_societe.rowid')
            ->where('llx_supplier_proposal.entity', $entity);

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('llx_supplier_proposal.ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['societe'])) {
            $query->where('llx_societe.nom', 'like', '%' . $search['societe'] . '%');
        }

        if (isset($search['fk_statut'])) {
            $query->where('llx_supplier_proposal.fk_statut', $search['fk_statut']);
        }

        if (!empty($search['date_debut']) && !empty($search['date_fin'])) {
            $query->whereBetween('llx_supplier_proposal.datep', [
                $search['date_debut'],
                $search['date_fin']
            ]);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $sortfield = str_replace('sp.', 'llx_supplier_proposal.', $sortfield);
        $sortfield = str_replace('s.', 'llx_societe.', $sortfield);
        
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $proposals = $query->get();

        return [
            'proposals' => $proposals,
            'total' => $total,
        ];
    }

    /**
     * Get a single supplier proposal by ID
     *
     * @param int $id Proposal ID
     * @return SupplierProposal|null
     */
    public function getById(int $id): ?SupplierProposal
    {
        return SupplierProposal::with(['societe', 'lines'])->find($id);
    }

    /**
     * Get supplier proposal by reference
     *
     * @param string $ref Proposal reference
     * @return SupplierProposal|null
     */
    public function getByRef(string $ref): ?SupplierProposal
    {
        return SupplierProposal::with(['societe', 'lines'])
            ->where('ref', $ref)
            ->first();
    }

    /**
     * Get proposals for a specific supplier
     *
     * @param int $supplierId Supplier ID
     * @param int $entity Entity ID
     * @return Collection
     */
    public function getBySupplier(int $supplierId, int $entity = 1): Collection
    {
        return SupplierProposal::where('fk_soc', $supplierId)
            ->where('entity', $entity)
            ->orderBy('datep', 'DESC')
            ->get();
    }

    /**
     * Create a new supplier proposal
     *
     * @param array $data Proposal data
     * @return SupplierProposal
     */
    public function create(array $data): SupplierProposal
    {
        return SupplierProposal::create($data);
    }

    /**
     * Update an existing supplier proposal
     *
     * @param int $id Proposal ID
     * @param array $data Updated data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $proposal = SupplierProposal::findOrFail($id);
        return $proposal->update($data);
    }

    /**
     * Delete a supplier proposal
     *
     * @param int $id Proposal ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $proposal = SupplierProposal::findOrFail($id);
        return $proposal->delete();
    }

    /**
     * Update proposal status
     *
     * @param int $id Proposal ID
     * @param int $status New status
     * @return bool
     */
    public function updateStatus(int $id, int $status): bool
    {
        $proposal = SupplierProposal::findOrFail($id);
        return $proposal->update(['fk_statut' => $status]);
    }

    /**
     * Get proposal statistics
     *
     * @param int $entity Entity ID
     * @return array
     */
    public function getStatistics(int $entity = 1): array
    {
        $stats = SupplierProposal::where('entity', $entity)
            ->select([
                'fk_statut',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_ttc) as total')
            ])
            ->groupBy('fk_statut')
            ->get()
            ->keyBy('fk_statut');

        return [
            'draft' => $stats->get(0, (object)['count' => 0, 'total' => 0]),
            'validated' => $stats->get(1, (object)['count' => 0, 'total' => 0]),
            'signed' => $stats->get(2, (object)['count' => 0, 'total' => 0]),
            'refused' => $stats->get(4, (object)['count' => 0, 'total' => 0]),
        ];
    }
}
