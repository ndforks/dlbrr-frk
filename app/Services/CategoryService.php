<?php

namespace App\Services;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * Get paginated list of categories
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int|null $type Category type filter
     * @param int $entity Entity ID
     * @return array Array containing categories and total count
     */
    public function getList(
        array $search = [],
        string $sortfield = 'label',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        ?int $type = null,
        int $entity = 1
    ): array {
        $query = Categorie::query()
            ->where('entity', $entity);

        // Filter by type if specified
        if ($type !== null) {
            $query->where('type', $type);
        }

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (!empty($search['description'])) {
            $query->where('description', 'like', '%' . $search['description'] . '%');
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $categories = $query->get();

        return [
            'categories' => $categories,
            'total' => $total,
        ];
    }

    /**
     * Get a single category by ID
     *
     * @param int $id Category ID
     * @return Categorie|null
     */
    public function getById(int $id): ?Categorie
    {
        return Categorie::find($id);
    }

    /**
     * Get categories by type
     *
     * @param int $type Category type (0=product, 1=supplier, 2=customer, etc.)
     * @param int $entity Entity ID
     * @return Collection
     */
    public function getByType(int $type, int $entity = 1): Collection
    {
        return Categorie::where('type', $type)
            ->where('entity', $entity)
            ->orderBy('label', 'ASC')
            ->get();
    }

    /**
     * Get category tree for a specific type
     *
     * @param int $type Category type
     * @param int $entity Entity ID
     * @return array
     */
    public function getTree(int $type, int $entity = 1): array
    {
        $categories = $this->getByType($type, $entity);
        
        // Build tree structure
        $tree = [];
        $indexed = [];
        
        foreach ($categories as $category) {
            $indexed[$category->rowid] = $category;
            $category->children = [];
        }
        
        foreach ($categories as $category) {
            if ($category->fk_parent > 0 && isset($indexed[$category->fk_parent])) {
                $indexed[$category->fk_parent]->children[] = $category;
            } else {
                $tree[] = $category;
            }
        }
        
        return $tree;
    }

    /**
     * Create a new category
     *
     * @param array $data Category data
     * @return int|null Category ID or null on failure
     */
    public function create(array $data): ?int
    {
        $category = Categorie::create($data);
        return $category ? $category->rowid : null;
    }

    /**
     * Update an existing category
     *
     * @param int $id Category ID
     * @param array $data Updated data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $category = Categorie::findOrFail($id);
        return $category->update($data);
    }

    /**
     * Delete a category
     *
     * @param int $id Category ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $category = Categorie::findOrFail($id);
        
        // Check if category has children
        $hasChildren = Categorie::where('fk_parent', $id)->exists();
        if ($hasChildren) {
            throw new \Exception('Cannot delete category with children');
        }
        
        return $category->delete();
    }

    /**
     * Get objects linked to a category
     *
     * @param int $categoryId Category ID
     * @param string $type Object type (product, customer, supplier, etc.)
     * @return Collection
     */
    public function getLinkedObjects(int $categoryId, string $type): Collection
    {
        $tableName = $this->getObjectTableName($type);
        
        return DB::table('llx_categorie_' . $tableName)
            ->where('fk_categorie', $categoryId)
            ->get();
    }

    /**
     * Get the table name for category links based on type
     *
     * @param string $type Object type
     * @return string
     */
    private function getObjectTableName(string $type): string
    {
        return match($type) {
            'product' => 'product',
            'customer', 'prospect' => 'societe',
            'supplier' => 'fournisseur',
            'contact' => 'contact',
            'member' => 'member',
            default => $type,
        };
    }
    
    /**
     * Get count of categories by type
     *
     * @param int $entity Entity ID
     * @return array Array with type as key and count as value
     */
    public function getCountByType(int $entity = 1): array
    {
        $results = Categorie::selectRaw('type, COUNT(rowid) as nb')
            ->where('entity', $entity)
            ->groupBy('type')
            ->get();
        
        $counts = [];
        foreach ($results as $result) {
            $counts[$result->type] = $result->nb;
        }
        
        return $counts;
    }
    
    /**
     * Get formatted category types array
     *
     * @param array $countobjects Category counts by type
     * @param object $langs Language object
     * @return array Formatted array of category types
     */
    public function getCategoryTypes(array $countobjects, $langs): array
    {
        // Category type constants from Dolibarr
        $mapId = [
            'product' => 0,
            'supplier' => 1,
            'customer' => 2,
            'member' => 3,
            'contact' => 4,
            'account' => 5,
            'project' => 6,
            'user' => 7,
            'bank_line' => 8,
            'warehouse' => 9,
            'actioncomm' => 10,
            'website_page' => 11,
            'ticket' => 12,
            'knowledgemanagement' => 13,
        ];
        
        $mapTypeTitleArea = [
            'product' => 'ProductsCategoriesArea',
            'supplier' => 'SuppliersCategoriesArea',
            'customer' => 'CustomersCategoriesArea',
            'member' => 'MembersCategoriesArea',
            'contact' => 'ContactCategoriesArea',
            'account' => 'AccountsCategoriesArea',
            'project' => 'ProjectsCategoriesArea',
            'user' => 'UsersCategoriesArea',
            'bank_line' => 'BanksCategoriesArea',
            'warehouse' => 'WarehousesCategoriesArea',
            'actioncomm' => 'ActioncommCategoriesArea',
            'website_page' => 'WebsitePagesCategoriesArea',
            'ticket' => 'TicketsCategoriesArea',
            'knowledgemanagement' => 'KnowledgeManagementArea',
        ];
        
        $arrayofcateg = [];
        
        foreach ($mapId as $key => $idtype) {
            $label = $langs->transnoentitiesnoconv($mapTypeTitleArea[$key] ?? $key);
            $arrayofcateg[$key] = [
                'key' => $key,
                'nb' => $countobjects[$idtype] ?? 0,
                'label' => $label,
                'labelwithoutaccent' => dol_string_unaccent($label),
            ];
        }
        
        // Sort by label
        usort($arrayofcateg, function($a, $b) {
            return strcmp($a['labelwithoutaccent'], $b['labelwithoutaccent']);
        });
        
        return $arrayofcateg;
    }
}
