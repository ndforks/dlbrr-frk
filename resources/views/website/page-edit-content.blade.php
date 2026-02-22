<!DOCTYPE html>
<html>
<head>
    <title>Edit Page Content - {{ $website->ref }}</title>
</head>
<body>
    <h1>Edit Page Content: {{ $page->title }}</h1>
    
    <form method="POST" action="">
        @csrf
        <input type="hidden" name="action" value="updatecontent">
        <input type="hidden" name="websiteid" value="{{ $website->id }}">
        <input type="hidden" name="pageid" value="{{ $page->id }}">
        
        <div>
            <textarea name="PAGE_CONTENT" rows="30" cols="100">{{ $page->content }}</textarea>
        </div>
        
        <div>
            <button type="submit">Update Content</button>
            <a href="?pageid={{ $page->id }}&websiteid={{ $website->id }}">Cancel</a>
        </div>
    </form>
</body>
</html>
