@if($users->count())
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role ?? 'N/A') }}</td>
                <td>
                    <button class="btn btn-sm btn-danger delete-user" data-id="{{ $user->id }}">
                        Excluir
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->withQueryString()->links() }}
@else
    <div class="alert alert-warning">Nenhum usuário encontrado.</div>
@endif
