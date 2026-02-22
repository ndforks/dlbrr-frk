<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Modules\Website\class\Website;
use App\Modules\Website\class\WebsitePage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;

class PageWebsite extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse|Response
    {
        global $conf, $db, $user, $langs;

        // Security check
        if (!$user->hasRight('website', 'read')) {
            accessforbidden();
        }

        // Get parameters
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $websiteid = GETPOSTINT('websiteid');
        $websitekey = GETPOST('website', 'alpha');
        $pageid = GETPOSTINT('pageid');
        $pageref = GETPOST('pageref', 'alphanohtml');
        $confirm = GETPOST('confirm', 'alpha');

        // Load objects
        $object = new Website($db);
        $objectpage = new WebsitePage($db);

        // Load website
        if ($websiteid > 0 || $websitekey) {
            $res = $object->fetch($websiteid, $websitekey);
            if ($res <= 0) {
                accessforbidden('Website not found');
            }
        } else {
            accessforbidden('Website parameter required');
        }

        // Load page
        if ($pageid > 0 || $pageref) {
            $res = $objectpage->fetch($pageid, $object->id, $pageref);
            if ($res <= 0) {
                $res = $objectpage->fetch($pageid, $object->id, null, $pageref);
            }
            if ($res <= 0) {
                accessforbidden('Page not found');
            }
        }

        $usercanedit = $user->hasRight('website', 'write');
        $permissiontodelete = $user->hasRight('website', 'delete');

        // Route to appropriate action handler
        return match($action) {
            'setashome' => $this->setAsHome($request, $object, $objectpage, $usercanedit),
            'editmeta' => $this->editMeta($request, $object, $objectpage, $usercanedit),
            'editsource' => $this->editSource($request, $object, $objectpage, $usercanedit),
            'editcontent' => $this->editContent($request, $object, $objectpage, $usercanedit),
            'updatemeta' => $this->updateMeta($request, $object, $objectpage, $usercanedit),
            'updatesource' => $this->updateSource($request, $object, $objectpage, $usercanedit),
            'updatecontent' => $this->updateContent($request, $object, $objectpage, $usercanedit),
            'delete' => $this->delete($request, $objectpage, $confirm, $permissiontodelete),
            'clone' => $this->clonePage($request, $object, $objectpage, $usercanedit),
            default => $this->show($request, $object, $objectpage),
        };
    }

    private function show(Request $request, $object, $objectpage): View
    {
        global $conf, $langs, $user;

        $langs->loadLangs(["website", "other"]);

        return view('website.page', [
            'website' => $object,
            'page' => $objectpage,
            'user' => $user,
            'conf' => $conf,
        ]);
    }

    private function setAsHome(Request $request, $object, $objectpage, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        global $db, $user;
        
        $object->fk_default_home = $objectpage->id;
        $result = $object->update($user);
        
        if ($result > 0) {
            return redirect()->back()->with('success', 'Page set as homepage');
        }
        
        return redirect()->back()->with('error', 'Failed to set as homepage');
    }

    private function editMeta(Request $request, $object, $objectpage, $usercanedit): View
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        return view('website.page-edit-meta', [
            'website' => $object,
            'page' => $objectpage,
        ]);
    }

    private function editSource(Request $request, $object, $objectpage, $usercanedit): View
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        return view('website.page-edit-source', [
            'website' => $object,
            'page' => $objectpage,
        ]);
    }

    private function editContent(Request $request, $object, $objectpage, $usercanedit): View
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        return view('website.page-edit-content', [
            'website' => $object,
            'page' => $objectpage,
        ]);
    }

    private function updateMeta(Request $request, $object, $objectpage, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        global $db, $user;

        $objectpage->title = GETPOST('WEBSITE_TITLE', 'alphanohtml');
        $objectpage->description = GETPOST('WEBSITE_DESCRIPTION', 'alphanohtml');
        $objectpage->keywords = GETPOST('WEBSITE_KEYWORDS', 'alphanohtml');
        $objectpage->lang = GETPOST('WEBSITE_LANG', 'aZ09');
        $objectpage->aliasalt = GETPOST('WEBSITE_ALIASALT', 'alphanohtml');
        $objectpage->pageurl = GETPOST('WEBSITE_PAGENAME', 'alpha');

        $result = $objectpage->update($user);
        
        if ($result > 0) {
            return redirect()->route('website.page', [
                'websiteid' => $object->id,
                'pageid' => $objectpage->id
            ])->with('success', 'Page metadata updated');
        }
        
        return redirect()->back()->with('error', 'Failed to update metadata');
    }

    private function updateSource(Request $request, $object, $objectpage, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        global $db, $user;

        $objectpage->content = GETPOST('PAGE_CONTENT', 'restricthtml');
        
        $result = $objectpage->update($user);
        
        if ($result > 0) {
            return redirect()->route('website.page', [
                'websiteid' => $object->id,
                'pageid' => $objectpage->id
            ])->with('success', 'Page source updated');
        }
        
        return redirect()->back()->with('error', 'Failed to update source');
    }

    private function updateContent(Request $request, $object, $objectpage, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        global $db, $user;

        $objectpage->content = GETPOST('PAGE_CONTENT', 'restricthtml');
        
        $result = $objectpage->update($user);
        
        if ($result > 0) {
            return redirect()->route('website.page', [
                'websiteid' => $object->id,
                'pageid' => $objectpage->id
            ])->with('success', 'Page content updated');
        }
        
        return redirect()->back()->with('error', 'Failed to update content');
    }

    private function delete(Request $request, $objectpage, $confirm, $permissiontodelete): RedirectResponse
    {
        if (!$permissiontodelete) {
            accessforbidden();
        }

        global $db, $user;

        if ($confirm === 'yes') {
            $result = $objectpage->delete($user);
            
            if ($result > 0) {
                return redirect()->route('website.index')->with('success', 'Page deleted');
            }
            
            return redirect()->back()->with('error', 'Failed to delete page');
        }

        return redirect()->back()->with('error', 'Delete not confirmed');
    }

    private function clonePage(Request $request, $object, $objectpage, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }

        global $db, $user;

        $newpage = clone $objectpage;
        $newpage->id = null;
        $newpage->pageurl = $objectpage->pageurl . '-copy';
        $newpage->title = $objectpage->title . ' (Copy)';

        $result = $newpage->create($user);
        
        if ($result > 0) {
            return redirect()->route('website.page', [
                'websiteid' => $object->id,
                'pageid' => $newpage->id
            ])->with('success', 'Page cloned');
        }
        
        return redirect()->back()->with('error', 'Failed to clone page');
    }
}
