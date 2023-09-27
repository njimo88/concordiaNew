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
                <button class="me-2" onclick="deleteMembers()">Supprimer les membres</button>
                <button onclick="triggerJob()">Déclencher le Job</button>
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
            throw new Error('La réponse du réseau n\'était pas correcte');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        alert(data.status);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Il y a eu une erreur lors de la synchronisation avec ClickAsso.');
    });
}
function deleteMembers() {
    const year = prompt("Veuillez entrer l'année fiscale pour laquelle vous souhaitez supprimer les membres:");
    if (!year) return;

    fetch(`/delete-all-members-for-year/${year}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('La réponse du réseau n\'était pas correcte');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        alert(data.status);
    })
    .catch(error => {
    error.json().then(data => {
        console.error(data.message);
        alert(data.message);
    }).catch(() => {
        console.error('Error:', error);
        alert('Il y a eu une erreur lors de la suppression des membres.');
    });
});

}

</script>
@endsection