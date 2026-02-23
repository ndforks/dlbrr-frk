<?php

namespace App\Http\Controllers\Bookmarks;

use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarksIndex extends Controller
{
    private BookmarkService $service;
    
    public function __construct(BookmarkService $service)
    {
        $this->service = $service;
    }
    
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $user;
        
        // Early return for permission check
        if (!$user->hasRight('bookmark', 'lire')) {
            accessforbidden();
        }
        
        $action = $request->input('action');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'delete' => $this->delete($request, $id),
            default => $this->index($request),
        };
    }
    
    private function index(Request $request): View
    {
        global $user, $conf;
        
        $search_title = $request->input('search_title');
        $limit = $request->integer('limit', 0) ?: $conf->liste_limit;
        $sortfield = $request->input('sortfield', 'position');
        $sortorder = $request->input('sortorder', 'ASC');
        $page = $request->has('pageplusone') ? ($request->integer('pageplusone', 0) - 1) : $request->integer('page', 0);
        
        // Reset page on search or filter removal
        if ($page < 0 || $request->input('button_search') || $request->input('button_removefilter')) {
            $page = 0;
        }
        
        $offset = $limit * $page;
        
        // Build search filters
        $filters = [];
        if (!empty($search_title)) {
            $filters['title'] = $search_title;
        }
        
        // Get bookmarks from service
        $result = $this->service->getList(
            $filters,
            $sortfield,
            $sortorder,
            $limit,
            $offset,
            $user->id
        );
        
        return view('bookmarks.index', [
            'bookmarks' => $result['bookmarks'],
            'total' => $result['total'],
            'page' => $page,
            'limit' => $limit,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'search_title' => $search_title,
        ]);
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $user, $langs;
        
        // Early return for permission check
        if (!$user->hasRight('bookmark', 'supprimer')) {
            accessforbidden();
        }
        
        if ($id > 0) {
            $result = $this->service->delete($id);
            
            if ($result) {
                setEventMessages($langs->trans("RecordDeleted"), null, 'mesgs');
            } else {
                setEventMessages($langs->trans("ErrorDeleteFailed"), null, 'errors');
            }
        }
        
        return redirect()->route('bookmarks.index');
    }
}
        

