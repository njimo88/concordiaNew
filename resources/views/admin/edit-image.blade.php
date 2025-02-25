@extends('layouts.template')

@section('content')
    <main class="main" id="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h5 class="card-title text-primary">Modifier une image</h5>

        <div class="container mt-4 text-center">
            <input type="file" id="imageInput" accept="image/*" class="form-control mb-3">

            <div class="crop-container" style="max-width: 100%; overflow: hidden;">
                <img id="imagePreview" style="max-width: 100%; display: none;">
            </div>

            <button class="btn btn-primary mt-3" id="cropButton" disabled>Recadrer & Télécharger</button>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let cropper;

            const imageWidth = 2240;
            const imageHeight = 750;
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const cropButton = document.getElementById('cropButton');

            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';

                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(imagePreview, {
                            aspectRatio: imageWidth / imageHeight,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                            cropBoxResizable: false,
                            cropBoxMovable: true,
                            zoomable: true,
                            background: false
                        });

                        cropButton.disabled = false;
                    };

                    reader.readAsDataURL(file);
                }
            });

            cropButton.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: imageWidth,
                        height: imageHeight
                    });

                    if (!canvas) {
                        alert("Impossible de générer l'image recadrée.");
                        return;
                    }

                    canvas.toBlob(blob => {
                        const file = imageInput.files[0];
                        let fileName = file ? file.name.replace(/\s+/g, '_') :
                            'image.jpg';

                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `cropped_${fileName}`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    }, 'image/jpeg');
                }
            });
        });
    </script>
@endsection
