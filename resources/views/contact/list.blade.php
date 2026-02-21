<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <meta name="csrf-token" content="{{ newToken() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        .search-form {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .search-form input {
            margin: 5px;
            padding: 8px;
            width: 200px;
        }
        .search-form button {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            padding: 5px 10px;
            margin: 0 2px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .pagination span {
            padding: 5px 10px;
            margin: 0 2px;
        }
        .no-results {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contacts / Addresses</h1>
        
        <!-- Search Form -->
        <form method="GET" action="{{ url('/contact/list.php') }}" class="search-form">
            <input type="text" name="search_all" placeholder="Search all..." value="{{ $search['all'] ?? '' }}">
            <input type="text" name="search_lastname" placeholder="Last name" value="{{ $search['lastname'] ?? '' }}">
            <input type="text" name="search_firstname" placeholder="First name" value="{{ $search['firstname'] ?? '' }}">
            <input type="text" name="search_societe" placeholder="Company" value="{{ $search['societe'] ?? '' }}">
            <input type="text" name="search_email" placeholder="Email" value="{{ $search['email'] ?? '' }}">
            <input type="text" name="search_phone" placeholder="Phone" value="{{ $search['phone'] ?? '' }}">
            <button type="submit">Search</button>
            <a href="{{ url('/contact/list.php') }}"><button type="button">Reset</button></a>
        </form>
        
        <!-- Results Summary -->
        <div>
            <strong>Total: {{ $total }} contacts</strong>
            @if($total > $limit)
                <span> (Showing {{ $page * $limit + 1 }} to {{ min(($page + 1) * $limit, $total) }})</span>
            @endif
        </div>
        
        <!-- Contacts Table -->
        @if($contacts->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Mobile</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td>{{ $contact->rowid }}</td>
                            <td>
                                <a href="{{ url('/contact/card.php?id=' . $contact->rowid) }}">
                                    {{ $contact->lastname }}
                                </a>
                            </td>
                            <td>{{ $contact->firstname }}</td>
                            <td>
                                @if($contact->societe)
                                    <a href="{{ url('/societe/card.php?id=' . $contact->societe->rowid) }}">
                                        {{ $contact->societe->nom }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                @endif
                            </td>
                            <td>{{ $contact->phone }}</td>
                            <td>{{ $contact->phone_mobile }}</td>
                            <td>{{ $contact->address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            @if($total > $limit)
                <div class="pagination">
                    @if($page > 0)
                        <a href="{{ url('/contact/list.php?page=' . ($page - 1) . '&' . http_build_query($search)) }}">Previous</a>
                    @endif
                    
                    <span>Page {{ $page + 1 }} of {{ ceil($total / $limit) }}</span>
                    
                    @if(($page + 1) * $limit < $total)
                        <a href="{{ url('/contact/list.php?page=' . ($page + 1) . '&' . http_build_query($search)) }}">Next</a>
                    @endif
                </div>
            @endif
        @else
            <div class="no-results">
                <p>No contacts found matching your search criteria.</p>
            </div>
        @endif
    </div>
</body>
</html>
