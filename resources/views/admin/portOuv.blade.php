@extends('layouts.template')

@section('content')
<main class="main" id="main">


<table id="users-table" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

@push('scripts')
<script>

</script>
@endpush
</main>
@endsection