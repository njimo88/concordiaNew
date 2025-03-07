@foreach ($users as $n_users)
                                    <tr>
                                        <td class=" " style="width:200px">
                                            {{ $n_users->username }}
                                            @if (auth()->user()->roles->supprimer_edit_ajout_user)  
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Supprimer">
                                                    <img data-user-id="{{ $n_users->user_id }}" class="deleteUser editbtn2 mx-2" src="{{ asset('assets/images/delete.png') }}" alt="">
                                                </span>
                                            @endif
                                        </td>
                                        <td style="font-weight : bold;width:200px;">
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Editer le profil">
                                                <a  data-user-id="{{ $n_users->user_id }}"  type="button" class="editusermodal user-link a text-black "  href="#">
                                                    {{ $n_users->name }} <br>
                                                     &nbsp;&nbsp; {{ $n_users->lastname }} - ({{ $n_users->family_id }})
                                                     <img  class=" editbtn2 mx-2" src="{{ asset('assets/images/curriculum-vitae.png') }}">
                                                </a>
                                            </span>
                                        </td>
                                        <!-- Modal -->
                                                                             
                                        <td class="">
                                            <a href="tel:{{ $n_users->phone }}">{{ $n_users->phone }}</a>
                                             <br>
                                            @if($n_users->birthdate)
                                                <?php echo date("d/m/Y", strtotime($n_users->birthdate)); ?>
                                            @else
                                                $n_users->birthdate
                                            @endif
                                        </td>
                                        
                                        <td> 
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Les membres de ma famille">
                                                <img data-user-id="{{ $n_users->family_id }}"  type="button" class="familymem editbtn2 mx-2" src="{{ asset('assets/images/familyy.png') }}"> 
                                            </span>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Facture Famille">
                                            <img  data-family-id="{{ $n_users->family_id }}"  type="button" class="familybill editbtn2 mx-2" src="{{ asset('assets/images/icon.png') }}"> 
                                            </span>
                                            @if (auth()->user()->roles->reinitialiser_mot_de_passe_user)
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Réinitialiser le mot de passe">
                                                    <img data-user-id="{{ $n_users->user_id }}" class="Resetpass editbtn2 mx-2" src="{{ asset('assets/images/rotate.png') }}"> 
                                                </span>
                                            @endif
                                            
                                        </td>
                                        
                                    </tr>
                                   
                                @endforeach
                               
