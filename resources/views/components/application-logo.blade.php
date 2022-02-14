@if (file_exists(public_path() . '/img/ceco.png'))
    <img src="/img/{{ request()->slug_instalacion }}.png">
@else
    <img src="/img/tallerempresarial.png">
@endif