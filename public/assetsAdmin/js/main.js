(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => e.addEventListener(type, listener))
    } else {
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /**
   * Initiate quill editors
   */
  if (select('.quill-editor-default')) {
    new Quill('.quill-editor-default', {
      theme: 'snow'
    });
  }

  if (select('.quill-editor-bubble')) {
    new Quill('.quill-editor-bubble', {
      theme: 'bubble'
    });
  }

  if (select('.quill-editor-full')) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [{
            font: []
          }, {
            size: []
          }],
          ["bold", "italic", "underline", "strike"],
          [{
              color: []
            },
            {
              background: []
            }
          ],
          [{
              script: "super"
            },
            {
              script: "sub"
            }
          ],
          [{
              list: "ordered"
            },
            {
              list: "bullet"
            },
            {
              indent: "-1"
            },
            {
              indent: "+1"
            }
          ],
          ["direction", {
            align: []
          }],
          ["link", "image", "video"],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }

  /**
   * Initiate TinyMCE Editor
   */

  var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    imagetools_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_sticky: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    link_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_class_list: [{
        title: 'None',
        value: ''
      },
      {
        title: 'Some class',
        value: 'class-name'
      }
    ],
    importcss_append: true,
    file_picker_callback: function(callback, value, meta) {
      /* Provide file and text for the link dialog */
      if (meta.filetype === 'file') {
        callback('https://www.google.com/logos/google.jpg', {
          text: 'My text'
        });
      }

      /* Provide image and alt text for the image dialog */
      if (meta.filetype === 'image') {
        callback('https://www.google.com/logos/google.jpg', {
          alt: 'My alt text'
        });
      }

      /* Provide alternative source and posted for the media dialog */
      if (meta.filetype === 'media') {
        callback('movie.mp4', {
          source2: 'alt.ogg',
          poster: 'https://www.google.com/logos/google.jpg'
        });
      }
    },
    templates: [{
        title: 'New Table',
        description: 'creates a new table',
        content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
      },
      {
        title: 'Starting my story',
        description: 'A cure for writers block',
        content: 'Once upon a time...'
      },
      {
        title: 'New list with dates',
        description: 'New List with dates',
        content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
      }
    ],
    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image imagetools table',
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  });

  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(needsValidation)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })

  /**
   * Initiate Datatables
   */
  const datatables = select('.datatable', true)
  datatables.forEach(datatable => {
    new simpleDatatables.DataTable(datatable);
  })

  /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function() {
        select('.echart', true).forEach(getEchart => {
          echarts.getInstanceByDom(getEchart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

})();


/*my table Sort-------------------------------------------------------------------*/
$('#myTable').DataTable({
  info: false,
    bLengthChange: false,
    paging: false, // Désactiver la pagination
    lengthChange: false, 
    language: {
      search: "",
      searchPlaceholder: "Rechercher...",
      lengthMenu: "Afficher _MENU_ entrées",
      zeroRecords: "Aucun résultat trouvé",
      info: "Affichage de l'entrée _START_ à _END_ sur _TOTAL_ entrées",
      infoEmpty: "Affichage de l'entrée 0 à 0 sur 0 entrée",
      infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
      paginate: {
          first: "Premier",
          last: "Dernier",
          next: "Suivant",
          previous: "Précédent"
      }
    },
  order: [],
  drawCallback: function(settings) {
    var api = this.api();
    api.column(0, {
      order: 'applied'
    }).nodes();
  },
  columnDefs: [
    {
      targets: 3,
      type: 'datetime-dd-mm-yyyy'
    },{
      targets: 4,
      type: 'numeric-comma'
    }
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

 
$.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function ( d ) {
    var b = d.split(/\D/);
    return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5]);
};


$.fn.dataTable.ext.type.order['numeric-comma-pre'] = function ( d ) {
  
  return parseFloat(d.replace(' ', '').replace(',', '.'));
};


// Apply the search
$('#myTable thead input').on('keyup change', function() {
  table
    .column($(this).parent().index() + ':visible')
    .search(this.value)
    .draw();
});

$('#myTable').on('click', 'thead th', function() {
  var colIndex = $(this).index();
  var isAsc = $(this).hasClass('asc');
  table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
});
/*my table end-------------------------------------------------------------------*/
/*myTableArticle Sort-------------------------------------------------------------------*/
$('#ArticleTable').DataTable({
  info: true,
  paging: true, 
  lengthChange: true, 
  lengthMenu: [ [100, 500, -1], [100, 500, "Tout"] ],
  pageLength: 100,
  language: {
    search: "",
    searchPlaceholder: "Rechercher...",
    lengthMenu: "Afficher _MENU_ entrées",
    zeroRecords: "Aucun résultat trouvé",
    info: "Affichage de l'entrée _START_ à _END_ sur _TOTAL_ entrées",
    infoEmpty: "Affichage de l'entrée 0 à 0 sur 0 entrée",
    infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
    paginate: {
      first: "Premier",
      last: "Dernier",
      next: "Suivant",
      previous: "Précédent"
    }
  },
  order: [],
  drawCallback: function(settings) {
    var api = this.api();
    api.column(0, {
      order: 'applied'
    }).nodes();
  },
  columnDefs: [
    { targets: [6, 7], orderable: false },
    {
      targets: 2,
      type: 'datetime-dd-mm-yyyy'
    },
    {
      targets: 4,
      type: 'datetime-dd-mm-yyyy'
    }
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
});


 
$.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function ( d ) {
    var b = d.split(/\D/);
    return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5]);
};


$.fn.dataTable.ext.type.order['numeric-comma-pre'] = function ( d ) {
  
  return parseFloat(d.replace(' ', '').replace(',', '.'));
};


// Apply the search
$('#ArticleTable thead input').on('keyup change', function() {
  table
    .column($(this).parent().index() + ':visible')
    .search(this.value)
    .draw();
});

$('#ArticleTable').on('click', 'thead th', function() {
  var colIndex = $(this).index();
  var isAsc = $(this).hasClass('asc');
  table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
});
/*myTableArticle end-------------------------------------------------------------------*/
$('#myTableArticle').DataTable({
  info: false,
    bLengthChange: false,
    paging: false, // Désactiver la pagination
    lengthChange: false, 
    language: {
      search: "",
      searchPlaceholder: "Rechercher...",
      lengthMenu: "Afficher _MENU_ entrées",
      zeroRecords: "Aucun résultat trouvé",
      info: "Affichage de l'entrée _START_ à _END_ sur _TOTAL_ entrées",
      infoEmpty: "Affichage de l'entrée 0 à 0 sur 0 entrée",
      infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
      paginate: {
          first: "Premier",
          last: "Dernier",
          next: "Suivant",
          previous: "Précédent"
      }
    },
  order: [],
  drawCallback: function(settings) {
    var api = this.api();
    api.column(0, {
      order: 'applied'
    }).nodes();
  },
  columnDefs: [
    {
      targets: 3,
      type: 'datetime-dd-mm-yyyy'
    },{
      targets: 4,
      type: 'numeric-comma'
    }
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

 
$.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function ( d ) {
    var b = d.split(/\D/);
    return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5]);
};


$.fn.dataTable.ext.type.order['numeric-comma-pre'] = function ( d ) {
  
  return parseFloat(d.replace(' ', '').replace(',', '.'));
};


// Apply the search
$('#myTableArticle thead input').on('keyup change', function() {
  table
    .column($(this).parent().index() + ':visible')
    .search(this.value)
    .draw();
});

$('#myTableArticle').on('click', 'thead th', function() {
  var colIndex = $(this).index();
  var isAsc = $(this).hasClass('asc');
  table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
});
  
  $('#myTableabb').on('click', 'thead th', function() {
    var colIndex = $(this).index();
    var isAsc = $(this).hasClass('asc');
    table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
  });   $('#myTableabb').DataTable({
    info: false,
    bLengthChange: false,
    paging: false, // Désactiver la pagination
    lengthChange: false, 
    language: {
      search: "Rechercher&nbsp;:",
      lengthMenu: "Afficher _MENU_ entrées",
      zeroRecords: "Aucun résultat trouvé",
      info: "Affichage de l'entrée _START_ à _END_ sur _TOTAL_ entrées",
      infoEmpty: "Affichage de l'entrée 0 à 0 sur 0 entrée",
      infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
      paginate: {
          first: "Premier",
          last: "Dernier",
          next: "Suivant",
          previous: "Précédent"
      }
  },
    drawCallback: function(settings) {
      var api = this.api();
      api.column(0, {
        order: 'applied'
      }).nodes();
    },
    columnDefs: [
      {
        targets: 3,
        type: 'datetime-dd-mm-yyyy'
      }
    ]
  });
  
  $.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function ( d ) {
      var b = d.split(/\D/);
      return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5]);
  };
  
  // Apply the search
  $('#myTableabb thead input').on('keyup change', function() {
    table
      .column($(this).parent().index() + ':visible')
      .search(this.value)
      .draw();
  });
  
  $('#myTableabb').on('click', 'thead th', function() {
    var colIndex = $(this).index();
    var isAsc = $(this).hasClass('asc');
    table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
  });

  /*---------------------------------porte ouverte --------------------------------------*/
  $(function () {
    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/usersget",
        pageLength: 10000, 
        lengthChange: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json"
        },
        columns: [
            {data: 'full_name', name: 'full_name'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {
                data: 'birthdate', 
                name: 'birthdate',
                render: function (data, type, row) {
                    var date = new Date(data);
                    return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
                }
            },
            {data: 'phone', name: 'phone', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

  
  $('#users-table').on('click', '.Resetpass', function(){
    $('#Resetpass').modal('show');
  
    // Get the bill ID from the clicked element
    var user_id = $(this).data('user-id');
    
     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/admin/members/mdpUniverselmodal/' + user_id,
     success: function(data) {
        // Insert the old bills data into the modal body
        $('#ResetpassContainer').html(data);
     }
     });
     });


      $('#users-table').on('click', '.editusermodal', function() {
        $('#editusermodal').modal('show');
    
        // Get the bill ID from the clicked element
        var user_id = $(this).data('user-id');
    
        // Make an AJAX request to retrieve the old bills
        $.ajax({
          url: '/admin/members/editUser/' + user_id,
          success: function(data) {
    
             $('#editusermodalContainer').html(data);
             $('.countrypicker').countrypicker();
             $('.selectpicker').selectpicker();
          }
        });
      });
  
   /*---------------------------------sorting by column myTableMembers --------------------------------------*/
  
   $('#myTableMembers').DataTable({ 
    pageLength: 100,
    info: false,
    bLengthChange: false,
    language: {
       search: "Rechercher&nbsp;:",
       lengthMenu: "Afficher _MENU_ entrées",
       zeroRecords: "Aucun résultat trouvé",
       info: "Affichage de l'entrée _START_ à _END_ sur _TOTAL_ entrées",
       infoEmpty: "Affichage de l'entrée 0 à 0 sur 0 entrée",
       infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
       paginate: {
           first: "Premier",
           last: "Dernier",
           next: "Suivant",
           previous: "Précédent"
       }
   },
   order: [[1, 'asc']],
   drawCallback: function(settings) {
     var api = this.api();
     api.column(1, {
       order: 'applied'
     }).nodes();
   },
   columnDefs: [
     {
       targets: 3,
       type: 'datetime-dd-mm-yyyy'
     },
     {
       targets: 4,
       sortable: false
     }
   ]
 });
 
 
 $.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function ( d ) {
     var b = d.split(/\D+/);
     return new Date(b[2], b[1] - 1, b[0], 0, 0, 0);
 };
 
 
 // Appliquer la recherche
 $('#myTableMembers thead input').on('keyup change', function() {
   table
     .column($(this).parent().index() + ':visible')
     .search(this.value)
     .draw();
 });
 
 $('#myTableMembers').on('click', 'thead th', function() {
   var colIndex = $(this).index();
   var isAsc = $(this).hasClass('asc');
   table.order([colIndex, isAsc ? 'asc' : 'desc']).draw();
 });

  
  

  /*---------------------------------modal myTableMembers familymem--------------------------------------*/
  
  $('#myTableMembers').on('click', '.familymem', function(){
       $('#familymem').modal('show');
     
       // Get the bill ID from the clicked element
       var user_id = $(this).data('user-id');
       
        // Make an AJAX request to retrieve the old bills
        $.ajax({
           
        url: '/admin/members/familleMembers/' + user_id,
        success: function(data) {
           // Insert the old bills data into the modal body
           $('#familymemContainer').html(data);
        }
        });
        });
  /*---------------------------------modal myTableMembers Resetpass--------------------------------------*/
  
  $('#myTableMembers').on('click', '.Resetpass', function(){
    $('#Resetpass').modal('show');
  
    // Get the bill ID from the clicked element
    var user_id = $(this).data('user-id');
    
     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/admin/members/mdpUniverselmodal/' + user_id,
     success: function(data) {
        // Insert the old bills data into the modal body
        $('#ResetpassContainer').html(data);
     }
     });
     });

  /*---------------------------------modal myTableMembers editusermodal--------------------------------------*/
  $(document).ready(function() {
    $('#myTableMembers').on('click', '.editusermodal', function() {
      $('#editusermodal').modal('show');
  
      // Get the bill ID from the clicked element
      var user_id = $(this).data('user-id');
  
      // Make an AJAX request to retrieve the old bills
      $.ajax({
        url: '/admin/members/editUser/' + user_id,
        success: function(data) {
  
           $('#editusermodalContainer').html(data);
           $('.countrypicker').countrypicker();
           $('.selectpicker').selectpicker();
        }
      });
    });
  });
  
  /*---------------------------------modal myTableMembers deleteUser---------------------------------------*/
  $('#myTableMembers').on('click', '.deleteUser', function(){
       $('#deleteUser').modal('show');
     
       // Get the bill ID from the clicked element
       var user_id = $(this).data('user-id');
       
  
        // Make an AJAX request to retrieve the old bills
        $.ajax({
           
        url: '/admin/members/deletemodal/' + user_id,
        success: function(data) {
           // Insert the old bills data into the modal body
           $('#deleteUserContainer').html(data);
        }
        });
        });

  /*---------------------------------modal myTableMembers familybill---------------------------------------*/

  $('#myTable').on('click', '.familybill', function(){
    console.log('test');
    $('#factureFamille').modal('show');
  
    // Get the bill ID from the clicked element
    var family_id = $(this).data('family-id');
    
  
     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/admin/paiement/factureFamille/' + family_id,
     success: function(data) {
      if (data === 'Aucune facture trouvée.') {
        $('#familyBillsContainer').html('<div style="display: block; color: black; margin: auto; text-align: center; padding: 10px;">Aucune donnée disponible</div>');
    } else {
        $('#familyBillsContainer').html(data);
    }
     }
     });
     });

     
  $('#myTableMembers').on('click', '.familybill', function(){
    console.log('test');
    $('#factureFamille').modal('show');
  
    // Get the bill ID from the clicked element
    var family_id = $(this).data('family-id');
    
  
     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/admin/paiement/factureFamille/' + family_id,
     success: function(data) {
      if (data === 'Aucune facture trouvée.') {
        $('#familyBillsContainer').html('<div style="display: block; color: black; margin: auto; text-align: center; padding: 10px;">Aucune donnée disponible</div>');
    } else {
        $('#familyBillsContainer').html(data);
    }
     }
     });
     });
  /*---------------------------------modal--------------------------------------*/
  
   
  $('#myTable').on('click', '.bill', function(){
  

       $('#oldBillsModal').modal('show');
     
       // Get the bill ID from the clicked element
       var user_id = $(this).data('user-id');
  
        // Make an AJAX request to retrieve the old bills
        $.ajax({
           
        url: '/admin/paiement/facture/get-old-bills/' + user_id,
        success: function(data) {
          if (data === 'Aucune facture trouvée.') {
              $('#oldBillsContainer').html('<div style="display: block; color: black; margin: auto; text-align: center; padding: 10px;">Aucune donnée disponible</div>');
          } else {
              $('#oldBillsContainer').html(data);
          }
      }
      
        });
      });
  
      
      
        
      /*-------------------------------------------------------------------------------------------*/
      $(document).on('click', '.delete-bill', function(event) {
        event.preventDefault();
        // Récupérer l'ID de la facture à modifier
        var billId = $(this).data('id');
    
        // Afficher une boîte de dialogue pour demander la confirmation de la modification
            // Envoyer une requête AJAX pour modifier la facture
            fetch('/facture/' + billId, {
                method: 'PUT', // Changez ceci en 'PUT' ou 'PATCH'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ status: 1 }) // Envoyer le nouveau statut avec la requête
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error(error));
    });
    
    
      
    
/*------------------------------------------------------------------------------------Professionnel-----------------------------------------------------------------------------------------*/

        /*---------------------------------modal declarationList--------------------------------------*/
  $('.declarationList').click(function() {
    $('#declarationList').modal('show');
  
    // Get the bill ID from the clicked element
    var user_id = $(this).data('user-id');
    

     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/admin/Professionnels/gestion/declarationList/' + user_id,
     success: function(data) {
        // Insert the old bills data into the modal body
        $('#declarationListContainer').html(data);
     }
     });
     });


     


/* CKEDITOR */

 CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserBrowseUrl: "/elfinder/ckeditor",
        filebrowserUploadMethod: 'form',
        language: 'fr',
        on: {
		loaded: function() {
			ajaxRequest({method: "POST", url: action, redirectTo: redirectPage, form: form});
		}
	},

        toolbar: [{ name: 'document', items : [ 'Source','NewPage','Preview' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat','strikethrough', 'underline', 'subscript', 'superscript', '|' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
            { name: 'heading', items : ['heading', '|' ] },
            { name: 'alignment', items : ['alignment', '|' ] },
            { name: 'font', items : [ 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor', '|'] },
            { name: 'styles', items : [ 'Styles','Format' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','todoList',] },
            { name: 'insert', items :[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            { name: 'tools', items : [ 'Maximize','-','About' ] }

],


  
				uiColor: '#FFDC6E'
    });

  
/*affichage du formulaire */

$(document).ready(function() {
  var max_fields = 10;
  var wrapper = $(".input_fields_wrap");
  var add_button = $(".add_field_button");
  var x = 1;
  $(add_button).click(function(e) {
      e.preventDefault();
      $.ajax({
          type: 'GET',
          dataType: 'html',
          url: "{{ route('test_create_article') }}",
          success: function(msg) {
              if (x < max_fields) {
                  x++;
                  $(wrapper).append('<br><br><div class="small-12" id="mysession">Début <input type="datetime-local" name="startdate[]"/>Fin <input type="datetime-local" name="enddate[]"/>Salle'  + msg + '<a href="#" class="remove_field">Supprimer</a></div>')
              }
          }
      });
  });
  $(wrapper).on("click", ".remove_field", function(e) {
      e.preventDefault();
      $(this).parent('div').remove();
      x--;
  })
});



// Dynamicaly create form fields
let input_str = {
  title:'',
  forms: [
  {
  type: "text",
  name: "libelle",
  class: "form-control mb-2",
  placeholder: "Libelle"
  },
  {
    type: "number",
    name: "stock_ini_d",
    class: "form-control mb-2",
    placeholder: "stock initial..."
    },

  {
  type: "number",
  name: "stock_actuel_d",
  class: "form-control mb-2",
  placeholder: "Stock actuel..."
  }
  ],
  exportTo:$('#getData')
  };
  $(document).ready(() => {
  $(".addInput").click(function() {
  build_inputs($(this), input_str);
  });
  });
  let randId = 1;
  function build_inputs(e, configs=false) {
  if(!configs){
  configs = {title:"Slides",forms:[{type:"text",name:"libelle",class:"form-control mb-2",placeholder:"Enter Data..."}],exportTo:false};
  }
  let ind = $(".adp-slides").length > 0 ? $(".adp-slides").length + 1 : 1;
  let str = `<div id="${configs.title +
  "" +
  ind}" class="row adp-slides"><div class="col-md-10"><div class="form-group"><label><b>${
  configs.title
  } ${ind}</b></label>`;
  configs.forms.forEach(config => {
  str += `<input type="${config.type}" name="${config.name}" id="adpElem${randId}" class="adpInputs ${config.class}" data-rel="${configs.title+""+ind}" placeholder="${config.placeholder}">`;
  let currentVal = e
  .parent()
  .siblings()
  .val();
  $("#adpElem" + randId)
  .val(currentVal)
  .focus();
  e.parent()
  .siblings()
  .val("");
  randId++;
  });
  str += `</div></div><div class="col-md-2"><span class="badge badge-danger removeSlide" data-target="${configs.title +
  "" +
  ind}"><i class="fas fa-trash-alt"></i></span></div></div>`;
  $(".inputWrapper").append(str);
  $(".removeSlide").click(function() {
  $("#" + $(this).attr("data-target")).remove();
  });
  if(configs.exportTo){
  $('.adpInputs').blur(()=>{
  let data = []
  $.each($('.adp-slides'),(i,e)=>{
  let obj = {},parentObj={};
  $.each($(e).children().find('input'),(i,e)=>{
  obj[$(e).attr('name')] = $(e).val();
  });
  parentObj[$(e).attr('id')]=obj;
  data.push(parentObj)
  })
  $(configs.exportTo).val(JSON.stringify(data,null,2))
  })
  }
  }





  $('.openmodal').click(function() {
    $('#display_info_user').modal('show');
  
    // Get the bill ID from the clicked element
    var user_id = $(this).data('user-id');
    
  
     // Make an AJAX request to retrieve the old bills
     $.ajax({
        
     url: '/club/display_modal/' + user_id,
     success: function(data) {
        // Insert the old bills data into the modal body
        $('#just_display').html(data);

        $('.countrypicker').countrypicker();
     }

     });
     
});
  

$(document).ready(function() {
    $('#reductions').DataTable({
      "lengthChange": false,
      "pageLength": 10,
      "info": false, 
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        }
    });
});



/*my table Sort-------------------------------------------------------------------*/


/*my table end-------------------------------------------------------------------*/

/*-------------------------- modal edit roles  ------------------------------------*/






$(document).ready(function() {
  $('#updateStock').click(function(e) {
      e.preventDefault();

      $.ajax({
          url: "/stock/update",
          method: 'POST',
          data: {
              "_token": $('meta[name="csrf-token"]').attr('content'),
          },
          success: function(response) {
              alert(response.message);
          }
      });
  });
});


$(document).ready(function() {
  $('#filterStatus').click(function(e) {
      e.preventDefault();

      $.ajax({
          url: "/admin/paiement/facture", 
          method: 'GET',
          data: {
              "_token": $('meta[name="csrf-token"]').attr('content'),
              "statusLessThan10": true
          },
          success: function(response) {
            window.location.href = "/admin/paiement/facture?statusLessThan10=true";
        }
        
      });
  });
});

$(document).ready(function() {
  $('#oldBills').click(function(e) {
      e.preventDefault();

      $.ajax({
          url: "/admin/paiement/facture", 
          method: 'GET',
          data: {
              "_token": $('meta[name="csrf-token"]').attr('content'),
              "statusOldBills": true
          },
          success: function(response) {
            console.log(response);
            window.location.href = "/admin/paiement/facture?statusOldBills=true";
        }
        
      });
  });
});


function debounce(func, wait, immediate) {
  var timeout;
  return function() {
      var context = this, args = arguments;
      var later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
  };
}

$(document).ready(function() {
  var searchUser = debounce(function(element) {
      var query = $(element).val();
      var userSelect = $('.user-select');
      userSelect.empty();

      if (query.length >= 3) {
        $.ajax({
          url: '/search',
          type: 'GET',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
              query: query,
          },
          success: function(data) {
              if (data.length > 0) {
                  $.each(data, function(key, value) {
                      userSelect.append('<option value="' + value.user_id + '" data-family-id="' + value.family_id + '">' + value.lastname + ' ' + value.name +'</option>');
                  });
              } else {
                  userSelect.append('<option>Aucun utilisateur trouvé</option>');
              }
          }
      });
      
      }
  }, 300); 

  $("#user-search-input").on("keyup", function() {
      searchUser(this);
  });
});

$("#user-select").on("change", function() {
  var selectedOption = $(this).find("option:selected");
  console.log(selectedOption);
  var userId = selectedOption.val();
  var familyId = selectedOption.data('family-id');

  $("#user-id").val(userId);
  $("#family-id").val(familyId);

});

$("#save-button").on("click", function() {
  var selectedOption = $("#user-select option:selected");
  var userId = selectedOption.val();
  var familyId = selectedOption.data('family-id');

  $("#user-id").val(userId);
  $("#family-id").val(familyId);

  var paymentMethod = $("#payment-method").val();

  $.ajax({
    url: '/create-bill',
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        "user_id": userId,
        "family_id": familyId,
        "payment_method": paymentMethod
    },
    success: function(response) {
        alert(response.message);
        window.location.href = "/admin/paiement/facture";
    },
    error: function(xhr, status, error) {
        alert('Une erreur s\'est produite lors de la création de la facture: ' + error);
    }
});

});