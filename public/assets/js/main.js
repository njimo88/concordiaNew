/**
 * Template Name: Impact - v1.2.0
 * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
document.addEventListener('DOMContentLoaded', () => {
    'use strict'

    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader')
    if (preloader) {
        window.addEventListener('load', () => {
            preloader.remove()
        })
    }

    /**
     * Sticky Header on Scroll
     */
    const selectHeader = document.querySelector('#header')
    if (selectHeader) {
        let headerOffset = selectHeader.offsetTop
        let nextElement = selectHeader.nextElementSibling

        const headerFixed = () => {
            if (headerOffset - window.scrollY <= 0) {
                selectHeader.classList.add('sticked')
                if (nextElement)
                    nextElement.classList.add('sticked-header-offset')
            } else {
                selectHeader.classList.remove('sticked')
                if (nextElement)
                    nextElement.classList.remove('sticked-header-offset')
            }
        }
        window.addEventListener('load', headerFixed)
        document.addEventListener('scroll', headerFixed)
    }

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = document.querySelectorAll('#navbar a')

    function navbarlinksActive() {
        navbarlinks.forEach((navbarlink) => {
            if (!navbarlink.hash) return

            let section = document.querySelector(navbarlink.hash)
            if (!section) return

            let position = window.scrollY + 200

            if (
                position >= section.offsetTop &&
                position <= section.offsetTop + section.offsetHeight
            ) {
                navbarlink.classList.add('active')
            } else {
                navbarlink.classList.remove('active')
            }
        })
    }
    window.addEventListener('load', navbarlinksActive)
    document.addEventListener('scroll', navbarlinksActive)

    /**
     * Mobile nav toggle
     */
    const mobileNavShow = document.querySelector('.mobile-nav-show')
    const mobileNavHide = document.querySelector('.mobile-nav-hide')

    document.querySelectorAll('.mobile-nav-toggle').forEach((el) => {
        el.addEventListener('click', function (event) {
            event.preventDefault()
            mobileNavToogle()
        })
    })

    function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active')
        mobileNavShow.classList.toggle('d-none')
        mobileNavHide.classList.toggle('d-none')
    }

    /**
     * Hide mobile nav on same-page/hash links
     */
    document.querySelectorAll('#navbar a').forEach((navbarlink) => {
        if (!navbarlink.hash) return

        let section = document.querySelector(navbarlink.hash)
        if (!section) return

        navbarlink.addEventListener('click', () => {
            if (document.querySelector('.mobile-nav-active')) {
                mobileNavToogle()
            }
        })
    })

    /**
     * Toggle mobile nav dropdowns
     */
    const navDropdowns = document.querySelectorAll('.navbar .dropdown > a')

    navDropdowns.forEach((el) => {
        el.addEventListener('click', function (event) {
            if (document.querySelector('.mobile-nav-active')) {
                event.preventDefault()
                this.classList.toggle('active')
                this.nextElementSibling.classList.toggle('dropdown-active')

                let dropDownIndicator = this.querySelector(
                    '.dropdown-indicator',
                )
                dropDownIndicator.classList.toggle('bi-chevron-up')
                dropDownIndicator.classList.toggle('bi-chevron-down')
            }
        })
    })

    /**
     * Initiate glightbox
     */
    const glightbox = GLightbox({
        selector: '.glightbox',
    })

    /**
     * Scroll top button
     */
    const scrollTop = document.querySelector('.scroll-top')
    if (scrollTop) {
        const togglescrollTop = function () {
            window.scrollY > 100
                ? scrollTop.classList.add('active')
                : scrollTop.classList.remove('active')
        }
        window.addEventListener('load', togglescrollTop)
        document.addEventListener('scroll', togglescrollTop)
        scrollTop.addEventListener(
            'click',
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            }),
        )
    }

    /**
     * Initiate Pure Counter
     */
    new PureCounter()

    /**
     * Clients Slider
     */
    new Swiper('.clients-slider', {
        speed: 400,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 60,
            },
            640: {
                slidesPerView: 4,
                spaceBetween: 80,
            },
            992: {
                slidesPerView: 6,
                spaceBetween: 120,
            },
        },
    })

    /**
     * Init swiper slider with 1 slide at once in desktop view
     */
    new Swiper('.slides-1', {
        speed: 600,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    })

    /**
     * Init swiper slider with 3 slides at once in desktop view
     */
    new Swiper('.slides-3', {
        speed: 600,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 40,
            },

            1200: {
                slidesPerView: 3,
            },
        },
    })

    /**
     * Porfolio isotope and filter
     */
    let portfolionIsotope = document.querySelector('.portfolio-isotope')

    if (portfolionIsotope) {
        let portfolioFilter = portfolionIsotope.getAttribute(
            'data-portfolio-filter',
        )
            ? portfolionIsotope.getAttribute('data-portfolio-filter')
            : '*'
        let portfolioLayout = portfolionIsotope.getAttribute(
            'data-portfolio-layout',
        )
            ? portfolionIsotope.getAttribute('data-portfolio-layout')
            : 'masonry'
        let portfolioSort = portfolionIsotope.getAttribute(
            'data-portfolio-sort',
        )
            ? portfolionIsotope.getAttribute('data-portfolio-sort')
            : 'original-order'

        window.addEventListener('load', () => {
            let portfolioIsotope = new Isotope(
                document.querySelector('.portfolio-container'),
                {
                    itemSelector: '.portfolio-item',
                    layoutMode: portfolioLayout,
                    filter: portfolioFilter,
                    sortBy: portfolioSort,
                },
            )

            let menuFilters = document.querySelectorAll(
                '.portfolio-isotope .portfolio-flters li',
            )
            menuFilters.forEach(function (el) {
                el.addEventListener(
                    'click',
                    function () {
                        document
                            .querySelector(
                                '.portfolio-isotope .portfolio-flters .filter-active',
                            )
                            .classList.remove('filter-active')
                        this.classList.add('filter-active')
                        portfolioIsotope.arrange({
                            filter: this.getAttribute('data-filter'),
                        })
                        if (typeof aos_init === 'function') {
                            aos_init()
                        }
                    },
                    false,
                )
            })
        })
    }

    /**
     * Animation on scroll function and init
     */
    function aos_init() {
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
        })
    }
    window.addEventListener('load', () => {
        aos_init()
    })
})
/*achat page------------------------------------------------------------------*/
$('.commanderModal').click(function () {
    

    // Get the shop ID from the clicked element
    var shop_id = $(this).data('shop-id')

    // Get the selected user ID from the dropdown
    var selected_user_id = $('#buyers').val()

    var declinaison = $('#declinaisons').val()
    //if the user choose the right size 
    if($('#declinaisons').val() && declinaison=="choisirLataille")
    {
        //remove the line to avoid replications 
        if ($('#size-alert').length) {
            $('#size-alert').remove();
        }
        
      // add line that said choisir la taille
        $('#declinaisons').before(
            '<div id="size-alert" style="color: red;">Veuillez sélectionner une taille avant de commander </div>',
        )
       
        return false;   
    }
    $('#commanderModal').modal('show')
    // Get the quantity input value
    var qte = $('#qte').val()

    // Make an AJAX request to retrieve the old bills
    $.ajax({
        url: '/commanderModal/' + shop_id + '/' + selected_user_id,
        data: {
            qte: qte,
            declinaison: declinaison,
        },
        success: function (data) {
            // Insert the old bills data into the modal body
            $('#commanderModalContainer').html(data)
        },
    })
})

/*End achat page------------------------------------------------------------------*/
/*my table Sort-------------------------------------------------------------------*/
$('#myTable').DataTable({
    pageLength: 100,
    info: false,
    bLengthChange: false,
    order: [[1, 'asc']],
    drawCallback: function (settings) {
        var api = this.api()
        api.column(0, {
            order: 'applied',
        }).nodes()
    },
    columnDefs: [
        {
            targets: 3,
            type: 'datetime-dd-mm-yyyy',
        },
        {
            targets: 4,
            type: 'payment-total-amount',
        },
    ],
})

$.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function (d) {
    var b = d.split(/\D/)
    return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5])
}

$.fn.dataTable.ext.type.order['payment-total-amount-pre'] = function (d) {
    return parseFloat(d.replace(' ', '').replace(',', '.'))
}

// Apply the search
$('#myTable thead input').on('keyup change', function () {
    table
        .column($(this).parent().index() + ':visible')
        .search(this.value)
        .draw()
})

$('#myTable').on('click', 'thead th', function () {
    var colIndex = $(this).index()
    var isAsc = $(this).hasClass('asc')
    table.order([colIndex, isAsc ? 'asc' : 'desc']).draw()
})
/*my table end-------------------------------------------------------------------*/
/*my table myTablefacture-------------------------------------------------------------------*/
$('#myTablefacture').DataTable({
    pageLength: 100,
    info: false,

    language: {
        url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json',
    },
    bLengthChange: false,
    searching: false,
    order: [[1, 'asc']],
    drawCallback: function (settings) {
        var api = this.api()
        api.column(0, {
            order: 'applied',
        }).nodes()
    },
    columnDefs: [
        {
            targets: 3,
            type: 'datetime-dd-mm-yyyy',
        },
        {
            targets: 4,
            type: 'payment-total-amount',
        },
    ],
    language: {
        paginate: {
            previous:
                '<button type="button" class="btn btn-light mb-4"><i class="fa-solid fa-chevron-left"></i></button>',
            next: '<button type="button" class="btn btn-light mb-4"><i class="fa-solid fa-chevron-right"></i></button>',
        },
    },
})

$.fn.dataTable.ext.type.order['datetime-dd-mm-yyyy-pre'] = function (d) {
    var b = d.split(/\D/)
    return new Date(b[2], b[1] - 1, b[0], b[3], b[4], b[5])
}

$.fn.dataTable.ext.type.order['payment-total-amount-pre'] = function (d) {
    return parseFloat(d.replace(' ', '').replace(',', '.'))
}

// Apply the search
$('#myTablefacture thead input').on('keyup change', function () {
    table
        .column($(this).parent().index() + ':visible')
        .search(this.value)
        .draw()
})

$('#myTablefacture').on('click', 'thead th', function () {
    var colIndex = $(this).index()
    var isAsc = $(this).hasClass('asc')
    table.order([colIndex, isAsc ? 'asc' : 'desc']).draw()
})
/*my table end-------------------------------------------------------------------*/

/*Factures/Devis-------------------------------------------------------------------*/

document.addEventListener('DOMContentLoaded', function () {
    const factureCheckbox = document.getElementById('factureCheckbox')
    const devisCheckbox = document.getElementById('devisCheckbox')
    const tableRows = document.querySelectorAll('table tbody tr')
    const noDataMessage = document.getElementById('noDataMessage')

    factureCheckbox.addEventListener('change', function () {
        filterTableRows()
    })

    devisCheckbox.addEventListener('change', function () {
        filterTableRows()
    })

    function filterTableRows() {
        let dataFound = false
        for (let i = 0; i < tableRows.length; i++) {
            const billType = tableRows[i].getAttribute('data-bill-type')
            if (
                (billType === 'facture' && factureCheckbox.checked) ||
                (billType === 'devis' && devisCheckbox.checked)
            ) {
                tableRows[i].style.display = 'table-row'
                dataFound = true
            } else {
                tableRows[i].style.display = 'none'
            }
        }

        if (dataFound) {
            noDataMessage.style.display = 'none'
        } else {
            noDataMessage.style.display = 'block'
        }
    }

    filterTableRows()
})
/*Factures/Devis-------------------------------------------------------------------*/

/*familly page------------------------------------------------------------------*/
$('.detailsUser').click(function () {
    $('#detailsUser').modal('show')

    // Get the bill ID from the clicked element
    var user_id = $(this).data('user-id')

    // Make an AJAX request to retrieve the old bills
    $.ajax({
        url: '/users/family/detailsUser/' + user_id,
        success: function (data) {
            // Insert the old bills data into the modal body
            $('#detailsUserContainer').html(data)
        },
    })
})

$(document).ready(function () {
    checkScrollPosition()

    $(window).scroll(checkScrollPosition)

    function checkScrollPosition() {
        if ($(window).scrollTop() > 100) {
            $('.top-cta').fadeIn()
        } else {
            $('.top-cta').fadeOut()
        }
    }

    $('.top-cta').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 800)
        return false
    })
})

document.getElementById('file-input').addEventListener('change', function () {
    let file = this.files[0]
    let reader = new FileReader()

    reader.addEventListener('load', function () {
        document.getElementById('existing-image').src = reader.result
    })

    reader.readAsDataURL(file)
})
/*End familyy page-------------------------------------------------------------------*/

/*---------------------Warning SVG function--------------------*/
function getWarningLevelColor(level) {
    switch (level) {
        case 1:
            return "#ffde0a";
        case 2:
            return "#d72d2d";
        default:
            return null;
    }
}

function getWarningSvg(level) {
    let color = getWarningLevelColor(level);
    var svg = '';
    if (color) {
        svg = `
        <span>
            <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="-47.81 -47.81 573.75 573.75" xml:space="preserve">
                <g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(40.640625,40.640625), scale(0.83)">
                    <rect x="-47.81" y="-47.81" width="573.75" height="573.75" rx="286.875" fill="${color}" stroke-width="0"></rect>
                </g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.95625"></g>
                <g id="SVGRepo_iconCarrier">
                    <circle cx="239.904" cy="314.721" r="35.878"></circle>
                    <path d="M256.657,127.525h-31.9c-10.557,0-19.125,8.645-19.125,19.125v101.975c0,10.48,8.645,19.125,19.125,19.125h31.9 c10.48,0,19.125-8.645,19.125-19.125V146.65C275.782,136.17,267.138,127.525,256.657,127.525z"></path>
                    <path d="M239.062,0C106.947,0,0,106.947,0,239.062s106.947,239.062,239.062,239.062c132.115,0,239.062-106.947,239.062-239.062 S371.178,0,239.062,0z M239.292,409.734c-94.171,0-170.595-76.348-170.595-170.596c0-94.248,76.347-170.595,170.595-170.595 s170.595,76.347,170.595,170.595C409.887,333.387,333.464,409.734,239.292,409.734z"></path>
                </g>
            </svg>
        </span>
        `;
    }

    return svg;
}