<div class="container h-100 mt-6">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="row mx-auto h-100">
                <div id="gallery" class="carousel slide w-100 align-self-center" data-ride="carousel">
                    <div class="carousel-inner mx-auto w-90" role="listbox" data-target="#lightbox">
                        @foreach ($sponsor_logos as $sponsor_logo)
                            <div class="carousel-item">
                                <div class="col-lg-5 col-md-4 row align-items-center g-0">
                                    <img class="img-fluid" src="{{ asset('storage/sponsor_logos/' . $sponsor_logo) }}"
                                        data-slide-to="0">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="w-100">
                        <a class="carousel-control-prev w-auto" href="#gallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next w-auto" href="#gallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* bootstrap mod: carousel controls */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%2322313F' stroke-miterlimit='10' stroke-width='2' viewBox='0 0 34.589 66.349'%3E%3Cpath d='M34.168.8 1.7 33.268 34.168 65.735'/%3E%3C/svg%3E");
        height: 100px;
    }

    .carousel-control-next-icon {
        transform: rotate(180deg);
    }

    /* medium - display 4  */
    @media (min-width: 768px) {

        #gallery .carousel-inner .carousel-item-right.active,
        #gallery .carousel-inner .carousel-item-next {
            transform: translateX(33.33333%);
        }

        #gallery .carousel-inner .carousel-item-left.active,
        #gallery .carousel-inner .carousel-item-prev {
            transform: translateX(-33.33333%);
        }
    }

    /* large - display 5 */
    @media (min-width: 992px) {

        #gallery .carousel-inner .carousel-item-right.active,
        #gallery .carousel-inner .carousel-item-next {
            transform: translateX(20%);
        }

        #gallery .carousel-inner .carousel-item-left.active,
        #gallery .carousel-inner .carousel-item-prev {
            transform: translateX(-20%);
        }
    }

    #gallery .carousel-inner .carousel-item-right,
    #gallery .carousel-inner .carousel-item-left {
        transform: translateX(0);
    }


    /* gallery slider */
    #gallery .carousel-inner .carousel-item.active,
    #gallery .carousel-inner .carousel-item-next,
    #gallery .carousel-inner .carousel-item-prev {
        display: flex;

    }

    @media (max-width: 768px) {
        #gallery .carousel-inner .carousel-item>div {
            display: none;
        }

        #gallery .carousel-inner .carousel-item>div:first-child {
            display: block;
            text-align: center;
        }
    }

    /* bootstrap mod addons */
    .w-90 {
        width: 90%;
    }

    .col-5,
    .col-sm-5,
    .col-md-5,
    .col-lg-5,
    .col-xl-5 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-5 {
        flex: 0 0 20%;
        max-width: 20%;
    }

    @media (min-width: 576px) {
        .col-sm-5 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (min-width: 768px) {
        .col-md-5 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (min-width: 992px) {
        .col-lg-5 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (min-width: 1200px) {
        .col-xl-5 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
</style>

<script>
    jQuery('ready', function() {
        jQuery('#gallery').carousel({
            interval: 5000
            // interval: false
        });

        // DEBUG: En la primara iteracion no copia el primer elemento al final
        jQuery('#gallery.carousel .carousel-item').each(function() {
            let minPerSlide = 4;
            let next = jQuery(this).next();
            if (!next.length) {
                next = jQuery(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo(jQuery(this));

            for (let i = 0; i < minPerSlide; i++) {
                next = next.next();
                if (!next.length) {
                    next = jQuery(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo(jQuery(this));
            }
        });

        jQuery(".carousel-item:first-of-type").addClass("active");
        jQuery(".carousel-indicators:first-child").addClass("active");
    });
</script>
