<!DOCTYPE html>
<html>
<head>
    <title>Edit Page Metadata - {{ $website->ref }}</title>
</head>
<body>
    <h1>Edit Page Metadata</h1>
    
    <form method="POST" action="">
        @csrf
        <input type="hidden" name="action" value="updatemeta">
        <input type="hidden" name="websiteid" value="{{ $website->id }}">
        <input type="hidden" name="pageid" value="{{ $page->id }}">
        
        <div>
            <label>Title:</label>
            <input type="text" name="WEBSITE_TITLE" value="{{ $page->title }}" size="60">
        </div>
        
        <div>
            <label>Page URL:</label>
            <input type="text" name="WEBSITE_PAGENAME" value="{{ $page->pageurl }}" size="60">
        </div>
        
        <div>
            <label>Description:</label>
            <textarea name="WEBSITE_DESCRIPTION" rows="3" cols="60">{{ $page->description }}</textarea>
        </div>
        
        <div>
            <label>Keywords:</label>
            <input type="text" name="WEBSITE_KEYWORDS" value="{{ $page->keywords }}" size="60">
        </div>
        
        <div>
            <label>Language:</label>
            <input type="text" name="WEBSITE_LANG" value="{{ $page->lang }}" size="10">
        </div>
        
        <div>
            <label>Alternative Alias:</label>
            <input type="text" name="WEBSITE_ALIASALT" value="{{ $page->aliasalt }}" size="60">
        </div>
        
        <div>
            <button type="submit">Update Metadata</button>
            <a href="?pageid={{ $page->id }}&websiteid={{ $website->id }}">Cancel</a>
        </div>
    </form>
</body>
</html>
