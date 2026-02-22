@php
// Website page view - minimal implementation
// Full CMS interface should be implemented here
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>{{ $page->title ?? 'Website Page' }} - {{ $website->ref }}</title>
</head>
<body>
    <h1>{{ $page->title ?? 'Untitled Page' }}</h1>
    <div>
        <strong>Website:</strong> {{ $website->ref }}<br>
        <strong>Page URL:</strong> {{ $page->pageurl }}<br>
        <strong>Description:</strong> {{ $page->description }}<br>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <div class="page-content">
        {!! $page->content !!}
    </div>
    
    <div class="page-actions">
        <a href="?action=editmeta">Edit Metadata</a> |
        <a href="?action=editcontent">Edit Content</a> |
        <a href="?action=editsource">Edit Source</a> |
        <a href="?action=setashome">Set as Home</a> |
        <a href="?action=clone">Clone Page</a>
    </div>
</body>
</html>
