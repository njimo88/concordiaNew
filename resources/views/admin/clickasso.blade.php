@extends('layouts.template')

@section('content')
<style>
    iframe {
        width: 100%;
        height: 100vh;
    }   
</style>
<main class="main" id="main">
    <div class="container">
        <div class="row">
            <div class="col-6 my-4">
                <h1>ClickAsso</h1>
            </div>
            <div class="col-6 d-flex justify-content-end my-4">
                <button onclick="triggerJob()">Trigger Job</button>
            </div>
            <div class="col-12">
                <iframe id="test" src="https://application.clickasso.fr/Interfaces/IndexFFGymLogin?token={{ $cookie }}"  frameBorder="0"> </iframe>
            </div>
        </div>
    </div>
    
    
</main>
<script>
function triggerJob() {
    fetch('/sync-with-clickasso', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        alert(data.status);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error while syncing with ClickAsso.');
    });
}

</script>
@endsection