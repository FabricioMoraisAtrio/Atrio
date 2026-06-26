@extends('admin.layouts.app')
@section('title', 'Administradores')

@section('content')
@php
    $inp = 'width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px; color:var(--adm-text); box-sizing:border-box;';
    $lbl = 'display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;';
@endphp

<div style="display:grid; grid-template-columns:320px 1fr; gap:18px; align-items:start;">
    {{-- Form --}}
    <div class="adm-card" style="padding:20px;">
        <p id="adm-form-titulo" style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 16px;">Novo administrador</p>
        <form id="admin-form" method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            <input type="hidden" name="_method" id="adm-form-method" value="POST">
            <div style="margin-bottom:12px;">
                <label style="{{ $lbl }}">Nome</label>
                <input type="text" name="name" id="a-name" required style="{{ $inp }}">
            </div>
            <div style="margin-bottom:12px;">
                <label style="{{ $lbl }}">E-mail</label>
                <input type="email" name="email" id="a-email" required style="{{ $inp }}">
            </div>
            <div style="margin-bottom:16px;">
                <label style="{{ $lbl }}">Senha <span id="a-pass-hint" style="color:var(--adm-text-3); font-weight:400;"></span></label>
                <input type="password" name="password" id="a-password" style="{{ $inp }}">
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="adm-btn adm-btn-primary" style="flex:1; justify-content:center;">Salvar</button>
                <button type="button" onclick="resetAdminForm()" class="adm-btn adm-btn-ghost" id="adm-btn-cancelar" style="display:none;">Cancelar</button>
            </div>
        </form>
    </div>

    {{-- Lista --}}
    <div class="adm-card" style="overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:var(--adm-border-2); text-align:left;">
                    <th style="padding:12px 18px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Nome</th>
                    <th style="padding:12px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">E-mail</th>
                    <th style="padding:12px 18px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase; text-align:right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $a)
                <tr style="border-top:1px solid var(--adm-border-2);">
                    <td style="padding:12px 18px; font-weight:600; color:var(--adm-text);">
                        {{ $a->name }}
                        @if($a->id === auth('admin')->id())<span style="font-size:10px; font-weight:700; color:var(--adm-accent); background:#E8F1FE; padding:2px 7px; border-radius:20px; margin-left:6px;">VOCÊ</span>@endif
                    </td>
                    <td style="padding:12px 14px; color:var(--adm-text-2);">{{ $a->email }}</td>
                    <td style="padding:10px 18px; text-align:right; white-space:nowrap;">
                        <button type="button" class="adm-btn adm-btn-ghost" style="padding:6px 12px;" onclick='editAdmin(@json($a->only(['id','name','email'])))'>Editar</button>
                        @if($a->id !== auth('admin')->id())
                        <form method="POST" action="{{ route('admin.admins.destroy', $a) }}" style="display:inline;" onsubmit="return confirm('Remover este administrador?')">@csrf @method('DELETE')
                            <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 12px; color:var(--adm-red);">Excluir</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function editAdmin(a) {
    document.getElementById('adm-form-titulo').textContent = 'Editar administrador';
    document.getElementById('admin-form').action = '{{ url('superadmin/administradores') }}/' + a.id;
    document.getElementById('adm-form-method').value = 'PUT';
    document.getElementById('a-name').value = a.name;
    document.getElementById('a-email').value = a.email;
    document.getElementById('a-password').value = '';
    document.getElementById('a-pass-hint').textContent = '(deixe em branco para manter)';
    document.getElementById('adm-btn-cancelar').style.display = 'inline-flex';
    window.scrollTo({top:0, behavior:'smooth'});
}
function resetAdminForm() {
    var f = document.getElementById('admin-form');
    f.reset();
    f.action = '{{ route('admin.admins.store') }}';
    document.getElementById('adm-form-method').value = 'POST';
    document.getElementById('adm-form-titulo').textContent = 'Novo administrador';
    document.getElementById('a-pass-hint').textContent = '';
    document.getElementById('adm-btn-cancelar').style.display = 'none';
}
</script>
@endsection
