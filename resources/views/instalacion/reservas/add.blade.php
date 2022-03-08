@extends('layouts.admin')

@section('style')
    <style>
        .descripcion {
            font-weight: 300;
        }

        select.form-control {
            height: calc(1em + 0.75rem + 2px);
            padding: 0;
            padding-left: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        label.col-form-label {
            font-weight: bold;
        }
        .card-body>div:first-child{
            margin-bottom: 20px;
        }
        h1 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            color: rgb(52, 70, 147);
            font-weight: bold;
            line-height: 1.5rem;
            margin: 0;
        }

        .title-reserva-section h1{
            font-size: 1.75rem;
            text-transform: uppercase;
        }
        .horario {
            color: rgb(52, 70, 147);
            font-weight: bold;
        }

        body {
            font-weight: 600;
        }

        .fecha-title {
            font-size: 15px;
            color: rgb(52, 70, 147);
        }

        .navbar-end .navbar-item{
            font-weight: bold;
            color: #4a4a4a;
            padding: 0.5rem 0.75rem;
        }
        .navbar-end .navbar-item.active{
            color: #3273dc;
        }
        .navbar-end .navbar-item:hover{
            color: #3273dc;
            text-decoration: none;
        }
        .navbar{
            background: white;
        }
        .card-body{
            padding: 1.75rem;
        }
        .card{
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
            box-shadow: 0 1px 2px 0 rgb(33 33 33 / 14%), 0 0 1px 0 rgb(0 0 0 / 14%);
        }
        #app{
            height: 100vh;
        }
        body > div.page-container > div.page-content-wrapper > div.content.sm-gutter > div{
            padding: 0 !important;
        }
        .datos_new_client{
            margin: 0 !important;
            border-top: none !important;
        }
        .datos_new_client input{
            padding: 8px !important;
        }
    </style>
@endsection

@section('content')
<div id="app"  style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5)), url(/img/deportes/reserva-{{ strtolower($pista->tipo) }}.jpg);
    background-size:cover;background-position:bottom">
    <section class="hero is-medium">
        <div class="has-text-centered title-div title-reserva-section" style="padding-top:4.5rem;padding-bottom:3.5rem;margin-bottom:0">
            <h1 class="title text-center mb-0 text-white">Crear reserva</h1>
        </div>
    </section>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h1><i class="far fa-calendar-check mr-2"></i> {{ $pista->nombre }}</h1>
                                <div class="fecha-title">{{ date('d-m-Y', $fecha) }}</div>
                            </div>
                            {{-- <div class="row">
                                <div class="col">
                                </div>
                                <div class="col">
                                </div>
                            </div> --}}
                            <p class="descripcion">
                                Estás reservando para <em>{{ $pista->nombre }}</em>. Por favor, revise y confirme
                                los datos siguientes.
                            </p>
                            <form method="POST" action="#">
                                @csrf
                                <input type="hidden" name="secuencia" id="secuencia" value="{{ $secuencia }}">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Tipo:</label>
                                    <div class="col-sm-9">
                                        <div>{{ $pista->tipo }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                    <div class="col-sm-9">
                                        <div>{{ $pista->nombre }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                    <div class="col-sm-9">
                                        <div>{{ date('d/m/Y', $fecha) }} (<span
                                                class="horario">{{ date('H:i', $fecha) }} a <span
                                                    class="hfin">{{ date('H:i', strtotime(date('H:i', $fecha) . " +{$secuencia} minutes")) }}</span></span>)
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Cliente:</label>
                                    <div class="col-sm-9">
                                        <select required class="full-width select2 select-cliente" data-init-plugin="select2" name="user_id" id="user_id">
                                            <option></option>
                                            <option value="new_user">+ NUEVO CLIENTE</option>
                                            @foreach (auth()->user()->instalacion->users as $item)
                                                @if ($item->id != auth()->user()->id)
                                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="datos_new_client row border p-2" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Tarifa:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control full-width" name="tarifa" id="tarifa">
                                            @if ($pista->allow_more_res)
                                                @for ($i = 1; $i < $number+1; $i++)
                                                    <option
                                                        data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia * $i . ' minutes')) }}"
                                                        value="{{ $i }}">RESERVA {{ $secuencia * $i }} MINUTOS</option>
                                                @endfor
                                            @else
                                                <option
                                                    data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia  . ' minutes')) }}"
                                                    value="1">RESERVA {{ $secuencia }} MINUTOS</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if (auth()->user()->instalacion->configuracion->observaciones)
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Observaciones:</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="observaciones" rows="3"></textarea>
                                        </div>
                                    </div>
                                @endif
                                @foreach ($pista->all_campos_personalizados as $item)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label py-0">{{ $item->label }}:</label>
                                            <div class="col-sm-9">
                                                @if ($item->tipo == 'textarea')
                                                    <textarea class="form-control" name="campo_adicional[{{ $item->id }}]" rows="3" {{ $item->required ? 'required' : '' }}></textarea>
                                                @elseif($item->tipo == 'select')
                                                    <select class="form-control" name="campo_adicional[{{ $item->id }}]">
                                                        @foreach (unserialize($item->opciones) as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="{{ $item->tipo }}" name="campo_adicional[{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                                @endif
                                            </div>
                                        </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Enviar notificación correo:</label>
                                    <div class="col-sm-9">
                                        <div class="form-check complete">
                                            <input type="checkbox" id="sendmail" name="sendmail">
                                            <label for="sendmail">
                                              Enviar notificación
                                            </label>
                                          </div>
                                    </div>
                                </div>
                                <div class="form-group row mt-4">
                                    <label class="col-sm-3 col-form-label py-0"></label>
                                    <div class="col-sm-9 text-right d-flex justify-content-end" style="gap: 14px">
                                        <button type="submit" class="btn btn-info text-white">
                                            <i class="fas fa-check mr-2"></i> Reservar
                                        </button>
                                        <a href="/{{ request()->slug_instalacion }}/admin/reservas" class="cancel btn btn-danger">
                                            <i class="fas fa-times mr-2"></i> Cancelar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#tarifa').change(function(e) {
                e.preventDefault();
                $('.hfin').html($(this).find(`[value="${$(this).val()}"]`).data('hfin'));
            });

            $(".select2").select2({
                placeholder: "Selecciona un usuario"
            });

            $('.select-cliente').change(function (e) { 
                if ($(this).val() == 'new_user') {
                    $('.datos_new_client').show().html(`
                    <input required type="text" class="form-control col-sm-12 mb-2" name="name" placeholder="Nombre completo">
                    <input required type="email" class="form-control col-sm-6 mb-2" name="email" placeholder="Email">
                    <input required type="text" class="form-control col-sm-6 mb-2" name="tlfno" placeholder="Teléfono">
                    `);
                } else {
                    $('.datos_new_client').hide().html(``);
                }
                
            });
        });
    </script>
@endsection