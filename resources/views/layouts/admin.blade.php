@extends('layouts.app')

@section('content-app')
    @include('inc.sidebar')

    <div class="page-container ">
        @include('inc.header')
        <div class="page-content-wrapper ">
            <div class="content sm-gutter">
                {{-- <div data-pages="parallax">
                    <div class="container-fluid p-l-25 p-r-25 sm-p-l-0 sm-p-r-0">
                        <div class="inner">
                            <!-- START BREADCRUMB -->
                            <ol class="breadcrumb sm-p-b-5">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div> --}}
                <div class="container-fluid p-l-25 p-r-25 p-t-0 p-b-25 sm-padding-10">
                    @yield('content')
                </div>
            </div>
            <div class=" container-fluid  container-fixed-lg footer">
                <div class="copyright sm-text-center">


                    <td id="footer"><small>&copy; Desarrollado por <a href="http://tallerempresarial.es/">Taller Empresarial
                                2.0</a> </td>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- END COPYRIGHT -->
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <div class="overlay hide" data-pages="search">
        <!-- BEGIN Overlay Content !-->
        <div class="overlay-content has-results m-t-20">
            <!-- BEGIN Overlay Header !-->
            <div class="container-fluid">
                <!-- BEGIN Overlay Logo !-->
                <img class="overlay-brand" src="simply_white/assets/img/logo.png" alt="logo"
                    data-src="simply_white/assets/img/logo.png" data-src-retina="simply_white/assets/img/logo_2x.png"
                    width="78" height="22">
                <!-- END Overlay Logo !-->
                <!-- BEGIN Overlay Close !-->
                <a href="#" class="close-icon-light btn-link btn-rounded  overlay-close text-black">
                    <i class="pg-icon">close</i>
                </a>
                <!-- END Overlay Close !-->
            </div>
            <!-- END Overlay Header !-->
            <div class="container-fluid">
                <!-- BEGIN Overlay Controls !-->
                <input id="overlay-search" class="no-border overlay-search bg-transparent" placeholder="Search..."
                    autocomplete="off" spellcheck="false">
                <br>
                <div class="d-flex align-items-center">
                    <div class="form-check right m-b-0">
                        <input id="checkboxn" type="checkbox" value="1">
                        <label for="checkboxn">Search within page</label>
                    </div>
                    <p class="fs-13 hint-text m-l-10 m-b-0">Press enter to search</p>
                </div>
                <!-- END Overlay Controls !-->
            </div>
            <!-- BEGIN Overlay Search Results, This part is for demo purpose, you can add anything you like !-->
            <div class="container-fluid p-t-20">
                <span class="hint-text">
                    suggestions :
                </span>
                <span class="overlay-suggestions"></span>
                <br>
                <div class="search-results m-t-30">
                    <p class="bold">Pages Search Results: <span class="overlay-suggestions"></span></p>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div class="thumbnail-wrapper d48 circular bg-success text-white ">
                                    <img width="36" height="36" src="simply_white/assets/img/profiles/avatar.jpg"
                                        data-src="simply_white/assets/img/profiles/avatar.jpg"
                                        data-src-retina="simply_white/assets/img/profiles/avatar2x.jpg" alt="">
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin "><span class="semi-bold result-name">ice cream</span>
                                        on pages</h5>
                                    <p class="small-text hint-text">via john smith</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div class="thumbnail-wrapper d48 circular bg-success text-white ">
                                    <div>T</div>
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin "><span class="semi-bold result-name">ice cream</span>
                                        related topics</h5>
                                    <p class="small-text hint-text">via pages</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div class="thumbnail-wrapper d48 circular bg-success text-white ">
                                    <div>M
                                    </div>
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin "><span class="semi-bold result-name">ice cream</span>
                                        music</h5>
                                    <p class="small-text hint-text">via pagesmix</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                        </div>
                        <div class="col-md-6">
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div class="thumbnail-wrapper d48 circular bg-info text-white d-flex align-items-center">
                                    <i class="pg-icon">facebook</i>
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin "><span class="semi-bold result-name">ice cream</span>
                                        on facebook</h5>
                                    <p class="small-text hint-text">via facebook</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div
                                    class="thumbnail-wrapper d48 circular bg-complete text-white d-flex align-items-center">
                                    <i class="pg-icon">twitter</i>
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin ">Tweats on<span class="semi-bold result-name"> ice
                                            cream</span></h5>
                                    <p class="small-text hint-text">via twitter</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                            <!-- BEGIN Search Result Item !-->
                            <div class="d-flex m-t-15">
                                <!-- BEGIN Search Result Item Thumbnail !-->
                                <div class="thumbnail-wrapper d48 circular text-white bg-danger d-flex align-items-center">
                                    <i class="pg-icon">google_plus</i>
                                </div>
                                <!-- END Search Result Item Thumbnail !-->
                                <div class="p-l-10">
                                    <h5 class="no-margin ">Circles on<span class="semi-bold result-name"> ice
                                            cream</span></h5>
                                    <p class="small-text hint-text">via google plus</p>
                                </div>
                            </div>
                            <!-- END Search Result Item !-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Overlay Search Results !-->
        </div>
        <!-- END Overlay Content !-->
    </div>
@endsection
