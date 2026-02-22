<?php

namespace App\Services;

use App\Models\StockMouvement;
use App\Models\Entrepot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Get paginated list of stock movements
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing movements and total count
     */
    public function getMovements(
        array $search = [],
        string $sortfield = 'datem',
        string $sortorder = 'DESC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = StockMouvement::query()
            ->select([
                'llx_stock_mouvement.*',
                'llx_product.ref as product_ref',
                'llx_product.label as product_label',
                'llx_entrepot.ref as warehouse_ref',
                'llx_entrepot.label as warehouse_label'
            ])
            ->leftJoin('llx_product', 'llx_stock_mouvement.fk_product', '=', 'llx_product.rowid')
            ->leftJoin('llx_entrepot', 'llx_stock_mouvement.fk_entrepot', '=', 'llx_entrepot.rowid')
            ->where('llx_product.entity', $entity);

        // Apply search filters
        if (!empty($search['product_ref'])) {
            $query->where('llx_product.ref', 'like', '%' . $search['product_ref'] . '%');
        }

        if (!empty($search['warehouse'])) {
            $query->where('llx_entrepot.ref', 'like', '%' . $search['warehouse'] . '%');
        }

        if (!empty($search['lot'])) {
            $query->where('llx_stock_mouvement.batch', 'like', '%' . $search['lot'] . '%');
        }

        if (isset($search['fk_product'])) {
            $query->where('llx_stock_mouvement.fk_product', $search['fk_product']);
        }

        if (isset($search['fk_entrepot'])) {
            $query->where('llx_stock_mouvement.fk_entrepot', $search['fk_entrepot']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $sortfield = str_replace('sm.', 'llx_stock_mouvement.', $sortfield);
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $movements = $query->get();

        return [
            'movements' => $movements,
            'total' => $total,
        ];
    }

    /**
     * Get stock levels for all warehouses
     *
     * @param int|null $productId Optional product ID filter
     * @param int $entity Entity ID
     * @return Collection
     */
    public function getStockLevels(?int $productId = null, int $entity = 1): Collection
    {
        $query = DB::table('llx_product_stock as ps')
            ->select([
                'ps.fk_product',
                'ps.fk_entrepot',
                'ps.reel',
                'p.ref as product_ref',
                'p.label as product_label',
                'e.ref as warehouse_ref',
                'e.label as warehouse_label'
            ])
            ->leftJoin('llx_product as p', 'ps.fk_product', '=', 'p.rowid')
            ->leftJoin('llx_entrepot as e', 'ps.fk_entrepot', '=', 'e.rowid')
            ->where('p.entity', $entity);

        if ($productId !== null) {
            $query->where('ps.fk_product', $productId);
        }

        return $query->get();
    }

    /**
     * Get list of warehouses
     *
     * @param int $entity Entity ID
     * @return Collection
     */
    public function getWarehouses(int $entity = 1): Collection
    {
        return Entrepot::where('entity', $entity)
            ->where('statut', 1) // Active warehouses only
            ->orderBy('ref', 'ASC')
            ->get();
    }

    /**
     * Get warehouse by ID
     *
     * @param int $id Warehouse ID
     * @return Entrepot|null
     */
    public function getWarehouseById(int $id): ?Entrepot
    {
        return Entrepot::find($id);
    }

    /**
     * Get stock movement by ID
     *
     * @param int $id Movement ID
     * @return StockMouvement|null
     */
    public function getMovementById(int $id): ?StockMouvement
    {
        return StockMouvement::with(['product', 'warehouse'])->find($id);
    }

    /**
     * Create a stock movement
     *
     * @param array $data Movement data
     * @return StockMouvement
     */
    public function createMovement(array $data): StockMouvement
    {
        return StockMouvement::create($data);
    }

    /**
     * Get stock value for a warehouse
     *
     * @param int $warehouseId Warehouse ID
     * @return float
     */
    public function getWarehouseStockValue(int $warehouseId): float
    {
        $value = DB::table('llx_product_stock as ps')
            ->leftJoin('llx_product as p', 'ps.fk_product', '=', 'p.rowid')
            ->where('ps.fk_entrepot', $warehouseId)
            ->sum(DB::raw('ps.reel * p.price'));

        return (float) $value;
    }

    /**
     * Get total stock for a product across all warehouses
     *
     * @param int $productId Product ID
     * @return int
     */
    public function getTotalStock(int $productId): int
    {
        $total = DB::table('llx_product_stock')
            ->where('fk_product', $productId)
            ->sum('reel');

        return (int) $total;
    }
}
