
<table style="max-width:1000px" id="myTableMembers" class="table mb-5 cust-datatable dataTable no-footer border">
   <thead>
       <tr>
           <th class="border">username</th>
           <th class="border">Nom Prénom</th>
           <th class="border">Téléphone</th>
           <th class="border">D Naissance</th>
       </tr>
   </thead>
   <tbody>
 @foreach ($famille as $famille)
           <tr>
               <td>{{ $famille->username }}</td>
               <td style="font-weight : bold;">
                   <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Editer le profil">
                       <a data-toggle="modal" data-target="#editUser{{ $famille->user_id }}"  type="button" class="user-link a text-black "  href="">{{ $famille->name }} {{ $famille->lastname }}</a>
                   </span>
               </td>
               <!-- Modal -->
               <td>{{ $famille->phone }}</td>
               <td><?php echo date("d/m/Y", strtotime($famille->birthdate)); ?></td>
           </tr>
 @endforeach
</tbody>
</table>