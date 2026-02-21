<!DOCTYPE html>
<html>
<head>
    <title>Edit Page Source - {{ $website->ref }}</title>
</head>
<body>
    <h1>Edit Page Source: {{ $page->title }}</h1>
    
    <form method="POST" action="">
        @csrf
        <input type="hidden" name="action" value="updatesource">
        <input type="hidden" name="websiteid" value="{{ $website->id }}">
        <input type="hidden" name="pageid" value="{{ $page->id }}">
        
        <div>
            <label>HTML Source:</label>
            <textarea name="PAGE_CONTENT" rows="30" cols="100" style="font-family: monospace;">{{ $page->content }}</textarea>
        </div>
        
        <div>
            <button type="submit">Update Source</button>
            <a href="?pageid={{ $page->id }}&websiteid={{ $website->id }}">Cancel</a>
        </div>
    </form>
</body>
</html>
