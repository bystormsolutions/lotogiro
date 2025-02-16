<div class="row">
    <div class="col-md-12">
        @error('success')
        @push('scripts')
            <script>
                toastr["success"]("{{ $message }}")
            </script>
        @endpush
        @enderror
        @error('error')
        @push('scripts')
            <script>
                toastr["error"]("{{ $message }}")
            </script>
        @endpush
        @enderror
    </div>
    <div class="col-md-7">
        <div class="card card-info pb-5">
            <div class="card-header">
                <h3 class="card-title">Usuário</h3>
            </div>
            <div class="card-body">
                @if(Route::currentRouteName() == 'admin.settings.users.edit')
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status" name="status"
                               @isset($user->status) @if($user->status == 1) checked @endif @endisset>
                        <label class="custom-control-label" for="status">Ativo?</label>
                    </div>
                @endif
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               name="name"
                               maxlength="50" value="{{old('name', $user->name ?? null)}}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-8">
                        <label for="last_name">Sobrenome</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                               name="last_name"
                               maxlength="100" value="{{old('last_name', $user->last_name ?? null)}}">
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="indicador">ID Indicador</label>
                        <input type="number" class="form-control" id="indicador" name="indicador" value="{{old('indicador', $user->indicador ?? null)}}" maxlength="20">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email"
                               maxlength="100" value="{{old('email', $user->email ?? null)}}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                       {{ $message }}
                    </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               maxlength="15">
                        @if(Route::currentRouteName() == 'admin.settings.users.edit')
                            <small>*Em branco para não alterar</small>
                        @endif
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="confirm_password">Confirme a senha</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation" maxlength="15">
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                           {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Valores</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="commission">Porcentagem de Comissão</label>
                    <input type="text" class="form-control @error('commission') is-invalid @enderror" id="commission"
                           name="commission"
                           maxlength="100" value="{{old('commission', $user->commission ?? null)}}">
                    @error('commission')
                    <span class="invalid-feedback" role="alert">
                       {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="balanceAtual">Saldo Atual</label>
                    <input type="text" readonly class="form-control text-right" id="balanceAtual"
                           name="balanceAtual"
                           maxlength="100"
                           value="{{old('balance', !empty($user->balance) ? \App\Helper\Money::toReal($user->balance) : null)}}">

                    <label for="balance">Adicionar Saldo</label>
                    <input type="text" class="form-control @error('balance') is-invalid @enderror" id="balance"
                           name="balance"
                           maxlength="100">
                    @error('balance')
                    <span class="invalid-feedback" role="alert">
                       {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    @if(Route::currentRouteName() == 'admin.settings.users.edit')
                        <a href="{{route('admin.settings.users.statementBalance', $user->id)}}" class="btn btn-primary btn-block">Extrato de Saldo</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Funções</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    @can('update_role')
                        @if(isset($roles) && $roles->count() > 0)
                            @foreach($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input roles"
                                           id="role{{$role->id}}" value="{{$role->id}}"
                                           name="roles[]" @if($role->can) checked @else '' @endif>
                                    <label class="custom-control-label" for="role{{$role->id}}">{{$role->name}}</label>
                                </div>
                            @endforeach
                        @else
                            <p>Ainda não existe funções cadastradas.</p>
                        @endif
                    @else
                        @if(isset($roles) && $roles->count() > 0)
                            <ul class="list-group ">
                                @foreach($roles as $role)
                                    <li class="list-group-item">{{$role->name}}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <a href="{{route('admin.settings.users.index')}}">
            <button type="button" class="btn btn-block btn-outline-secondary">Voltar a tela principal</button>
        </a>
    </div>
    <div class="col-md-6 mb-3">
        <button type="submit"
                class="btn btn-block btn-outline-success">@if(request()->is('admin/settings/users/create')) Cadastrar
            Usuário  @else  Atualizar Usuário @endif </button>
    </div>
</div>

@push('scripts')
    <script src="{{asset('admin/layouts/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#balance").inputmask('currency', {
                "autoUnmask": true,
                radixPoint: ",",
                groupSeparator: ".",
                allowMinus: false,
                digits: 2,
                digitsOptional: false,
                rightAlign: true,
                unmaskAsNumber: true
            });
        });
    </script>
@endpush
