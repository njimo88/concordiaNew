@extends('layouts.template')
@section('content')
<style>
  .file-preview {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f1f1f1;
    padding: 5px;
    margin-bottom: 5px;
    border-radius: 3px;
}


  .list-group {
    border: 1px solid #ddd;
    max-height: 300px;
    overflow-y: auto;
}

  body {
      background-color: #f8f9fa;
      font-family: 'Roboto', sans-serif;
  }

  .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  }

  .card-header {
      background-color: #17a2b8;
      color: #fff;
  }

  .card-header h3 {
      font-weight: 500;
      color: black !important;
  }

  .btn-primary {
      background-color: #17a2b8;
      border-color: #17a2b8;
  }

  .btn-primary:hover {
      background-color: #138496;
      border-color: #138496;
  }

  .list-group-item {
      cursor: pointer;
  }

  .list-group-item:hover {
      background-color: #f1f1f1;
  }

  .form-check-input:checked {
      background-color: #17a2b8;
      border-color: #17a2b8;
  }
</style>
<script src="https://cdn.ckeditor.com/4.25.0-lts/full/ckeditor.js"></script>


<main class="main" id="main">
  <?php
$successMessage = $_GET['successMessage'] ?? '';

if (!empty($successMessage)) {
    echo '<div class="alert alert-success">' . $successMessage . '</div>';
}
?>
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="userModalLabel">Erreur</h5>
            </div>
            <div class="modal-body" id="userModalBody">

            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="responseModalLabel">Message</h5>
        </div>
        <div class="modal-body" id="responseModalBody">
        </div>
      </div>
    </div>
  </div>
  

      
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  
                  <div class="card-body">
                    
                    <form class="row" enctype="multipart/form-data">
                      <div class="card-header d-flex justify-content-between">
                        <h3>Envoyer un e-mail</h3>
                        <button id="send-button" type="submit" class="btn btn-primary">Envoyer<span id="loading-icon" style="display: none;"><i class="fa fa-spinner fa-spin"></i></span></button>
                      </div>
                      <div class="form-group col-4">
                        <label for="saison">Saison :</label>
                        <div class="input-group">
                          <select class="form-select" id="saison-select">
                            @foreach($saison_list as $saison)
                              <option value="{{ $saison->saison }}">{{ ucfirst($saison->saison) }}-{{ ucfirst($saison->saison+1) }}</option>
                            @endforeach
                          </select>
                          
                        </div>
                      </div>
                      <div class="form-group col-8">
                        <label for="saison">Groupes :</label>
                        <div class="input-group">
                          <select class="form-select" id="shop-articles" style="max-height: 300px; overflow-y: auto;">
                            @foreach($shop_articles as $shop_article)
                            <option value="Choisir un groupe" selected >Choisir un groupe</option>
                              <option value="{{ $shop_article->id_shop_article }}" data-saison="{{ $shop_article->saison }}">{{ $shop_article->title }}</option>
                            @endforeach
                          </select>
                          
                        </div>
                      </div>

                      <div class="form-group mt-3">
                        <div class="row justify-content-center">
                            <div class="col-md-4 border border-dark py-2">
                                <label>Utilisateurs :</label>
                                <a href="#" class="list-group-item">
                                  <input type="checkbox" class="form-check-input mx-2" id="check-all-unselected">
                                  <span>Sélectionner tous</span>
                               </a>
                                <div class="list-group mt-2" id="unselected-users">
                                </div>
                            </div>
                            <div class="col-md-2 mt-3 d-flex flex-column justify-content-center align-items-center">
                                <button type="button" class="btn btn-primary mx-auto" id="move-to-selected"><i class="fa-solid fa-angle-right"></i></button>
                                <button type="button" class="btn btn-primary mx-auto mt-2" id="move-to-unselected"><i class="fa-solid fa-angle-left"></i></button>
                            </div>
                            <div class="col-md-4 border border-dark py-2">
                                <label>Utilisateurs sélectionnés :</label>
                                <a href="#" class="list-group-item">
                                  <input type="checkbox" class="form-check-input mx-2" id="check-all-selected">
                                  <span>Sélectionner tous</span>
                               </a>
                                <div class="list-group" id="selected-users">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group mt-3">
                        <label>Titre </label>
                      <textarea style="height: 40px;" type="text" name="titre" class="form-control" required></textarea>
                      <br>
                    <label></label>
                    <textarea name="editor1" id="ckeditor" class="form-control" required></textarea>
                    <div class="form-group mt-3">
                      <label>Pièces jointes :</label>
                      <button id="add-file-btn" type="button" class="btn btn-primary">Ajouter un fichier</button>
                      <input type="file" name="attachments[]"  class="form-control" multiple style="display: none;">
                      <div id="selected-attachments" class="mt-2"></div>
                  </div>
                  
                  
                      </div>
                      
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const addFileBtn = document.getElementById('add-file-btn');
      const attachmentsInput = document.querySelector('input[name="attachments[]"]');
      const attachmentsContainer = document.getElementById('selected-attachments');
  
      let totalFiles = [];
  
      addFileBtn.addEventListener('click', function() {
          attachmentsInput.click();
      });
  
      attachmentsInput.addEventListener('change', async function() {
          const formData = new FormData();
  
          for (let i = 0; i < this.files.length; i++) {
              const file = this.files[i];
              formData.append('attachments[]', file);
  
              const fileDiv = document.createElement('div');
              fileDiv.className = 'file-preview';
              
              const fileNameSpan = document.createElement('span');
              fileNameSpan.textContent = file.name;
  
              const removeBtn = document.createElement('button');
              removeBtn.textContent = 'Supprimer';
              removeBtn.className = 'btn btn-danger btn-sm';
              removeBtn.type = 'button';
              removeBtn.addEventListener('click', async function() {
                  const index = totalFiles.indexOf(file);
                  if (index > -1) {
                      totalFiles.splice(index, 1);
                  }
  
                  const deleteData = new FormData();
                  deleteData.append('filename', file.name);
  
                  try {
                      const response = await fetch('{{ route("attachment.delete") }}', {
                          method: 'POST',
                          headers: {
                              'X-CSRF-TOKEN': '{{ csrf_token() }}'
                          },
                          body: deleteData
                      });
  
                      if (!response.ok) {
                          throw new Error('Network response was not ok');
                      }
  
                      fileDiv.remove();
                  } catch (error) {
                      console.error("There was a problem with the fetch operation:", error);
                  }
              });
  
              fileDiv.appendChild(fileNameSpan);
              fileDiv.appendChild(removeBtn);
              attachmentsContainer.appendChild(fileDiv);
  
              totalFiles.push(file);
          }
  
          try {
              const response = await fetch('{{ route("attachment.upload") }}', {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: formData
              });
  
              if (!response.ok) {
                  throw new Error('Network response was not ok');
              }
  
  
          } catch (error) {
              console.error("There was a problem with the fetch operation:", error);
          }
      });
  
      document.querySelector('form').addEventListener('submit', function(e) {
          const newFileList = new DataTransfer();
          totalFiles.forEach(file => newFileList.items.add(file));
          attachmentsInput.files = newFileList.files;
      });
  });
  </script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const shopArticles = document.getElementById('shop-articles');
      const saisonSelect = document.getElementById('saison-select');
      const unselectedUsers = document.getElementById('unselected-users');
      const selectedUsers = document.getElementById('selected-users');
      const moveToSelectedButton = document.getElementById('move-to-selected');
      const moveToUnselectedButton = document.getElementById('move-to-unselected');
      const checkAllUnselected = document.getElementById('check-all-unselected');
      const checkAllSelected = document.getElementById('check-all-selected');
  
      function filterShopArticles() {
          const selectedSaison = saisonSelect.value;
          const shopArticleOptions = shopArticles.querySelectorAll('option');
  
          shopArticleOptions.forEach(function (option) {
              const shopArticleSaison = option.dataset.saison;
              option.hidden = (shopArticleSaison !== selectedSaison && selectedSaison !== '');
          });
      }
  
      saisonSelect.addEventListener('change', filterShopArticles);
      filterShopArticles();
  
      shopArticles.addEventListener('change', function () {
          const selectedArticleId = this.value;
  
          fetch('/get-buyers-for-shop-article/' + selectedArticleId)
              .then(response => response.json())
              .then(buyers => {
                  unselectedUsers.innerHTML = '';
  
                  buyers.sort(function (a, b) {
                      if (a.lastname < b.lastname) return -1;
                      if (a.lastname > b.lastname) return 1;
                      return 0;
                  });
  
                  buyers.forEach(buyer => {
                      const listItem = document.createElement('a');
                      listItem.href = '#';
                      listItem.classList.add('list-group-item');
                      listItem.dataset.id = buyer.user_id; // Add the user ID to the data-id attribute

                      const checkbox = document.createElement('input');
                      checkbox.type = 'checkbox';
                      checkbox.classList.add('form-check-input', 'mx-2');
                      listItem.appendChild(checkbox);

                      const buyerName = document.createElement('span');
                      buyerName.textContent = buyer.name + ' ' + buyer.lastname;
                      listItem.appendChild(buyerName);

                      unselectedUsers.appendChild(listItem);
                  });

              });
              checkAllUnselected.checked = false;
            checkAllSelected.checked = false;
      });
  
      moveToSelectedButton.addEventListener('click', function () {
          const usersToMove = Array.from(unselectedUsers.querySelectorAll('input:checked')).map(input => input.parentNode);
  
          usersToMove.forEach(user => {
              user.querySelector('input').checked = false;
              const userText = user.textContent.trim();
              let alreadyExists = false;
  
              selectedUsers.querySelectorAll('a.list-group-item').forEach(selectedUser => {
                  if (selectedUser.textContent.trim() === userText) {
                      alreadyExists = true;
                  }
              });
  
              if (!alreadyExists) {
                  selectedUsers.appendChild(user);
              }
          });
          checkAllUnselected.checked = false;
            checkAllSelected.checked = false;
      });
  
      moveToUnselectedButton.addEventListener('click', function () {
          const usersToMove = Array.from(selectedUsers.querySelectorAll('input:checked')).map(input => input.parentNode);
          usersToMove.forEach(user => {
              user.querySelector('input').checked = false;
              unselectedUsers.appendChild(user);
          });
          checkAllUnselected.checked = false;
            checkAllSelected.checked = false;
      });
  
      checkAllUnselected.addEventListener('change', function () {
        const checkboxes = unselectedUsers.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = checkAllUnselected.checked;
        });
    });

    checkAllSelected.addEventListener('change', function () {
        const checkboxes = selectedUsers.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = checkAllSelected.checked;
        });
    });

    //send email
    const sendButton = document.getElementById('send-button');
sendButton.addEventListener('click', function (event) {
    console.log('clicked');
    event.preventDefault();
    const loadingIcon = document.getElementById('loading-icon');
    const selectedUserIds = Array.from(selectedUsers.querySelectorAll('a.list-group-item')).map(user => user.dataset.id);
    if (selectedUserIds.length === 0) {
        document.getElementById('userModalBody').innerText = "Aucun utilisateur sélectionné";
        $('#userModal').modal('show');
        return;
    }
    const selectedGroup = document.querySelector('#shop-articles option:checked').textContent;
    const subject = document.querySelector('textarea[name="titre"]').value;
    const content = CKEDITOR.instances.ckeditor.getData();
    const attachments = document.querySelector('input[name="attachments[]"]').files;

    if (subject.trim() === "") {
        document.getElementById('userModalBody').innerText = "Le titre est vide";
        $('#userModal').modal('show');
        return;
    }
    if (content.trim() === "") {
        document.getElementById('userModalBody').innerText = "Le contenu est vide";
        $('#userModal').modal('show');
        return;
    }
    sendButton.disabled = true;
    loadingIcon.style.display = '';
    fetch('/get-emails', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userIds: selectedUserIds
        })
    })
    .then(response => response.json())
    .then(emails => {
        const formData = new FormData();
        for (let i = 0; i < attachments.length; i++) {
            formData.append('attachments[]', attachments[i]);
        }
        formData.append('emails', JSON.stringify(emails));
        formData.append('subject', subject);
        formData.append('content', content);
        formData.append('group', selectedGroup);
        fetch('/send-emails', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const responseModal = document.getElementById('responseModal');
            const responseModalBody = document.getElementById('responseModalBody');

            // Get all child elements (e.g., spans) inside selectedUsers
            const names = Array.from(selectedUsers.children).map(child => child.textContent);
            // Join the names with " | " as a separator
            const formattedNames = names.join(' | ');

            responseModalBody.textContent = data.message + " à " + selectedUserIds.length + " utilisateurs qui sont : "+ formattedNames;
            $(responseModal).modal('show');
            sendButton.disabled = false;
            loadingIcon.style.display = 'none';
            checkAllSelected.checked = false;
        });
    });
    console.log(selectedUsers)
    console.log(selectedUserIds)
    console.log(selectedUserIds.length)
});


   
});


</script>
  
@endsection




