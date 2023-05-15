@extends('layouts.template')
@section('content')
<style>
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
<main class="main" id="main">
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  
                  <div class="card-body">
                    
                    <form class="row">
                      <div class="card-header d-flex justify-content-between">
                        <h3>Envoyer un e-mail</h3>
                        <button id="send-button" type="submit" class="btn btn-primary">Envoyer</button>
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
                      <textarea name="editor1"  id="ckeditor" class="form-control" required></textarea>
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
                      buyerName.textContent = buyer.lastname + ' ' + buyer.name;
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

    const sendButton = document.getElementById('send-button');

sendButton.addEventListener('click', function (event) {
    event.preventDefault();

    // Get the IDs of the selected users
    const selectedUserIds = Array.from(selectedUsers.querySelectorAll('a.list-group-item')).map(user => user.dataset.id);
    // Get the email subject and content from the form inputs
    const subject = document.querySelector('textarea[name="titre"]').value;
    const content = CKEDITOR.instances.ckeditor.getData();

    // Request the emails of the selected users from the server
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
      fetch('/send-emails', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify({
        emails: emails,
        subject: subject,
        content: content
    })
})
.then(response => response.json())
.then(data => {
    alert(data.message);
});


    });
});

});
</script>
  
@endsection




