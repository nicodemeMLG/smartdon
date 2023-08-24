<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des utilisateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("La liste des utilisateur du site!") }}
                </div>
            </div>
        </div>


        <div class="flex flex-col justify-center py-7 mx-12">
            <table class="table-fixed dark:text-white my-5 space-x-24 ">
                <thead class="">
                  <tr>
                    <th class="px-4 text-xl">Id</th>
                    <th class="px-4 text-xl">Nom</th>
                    <th class="px-4 text-xl">Prénom</th>
                    <th class="px-4 text-xl">Email</th>
                    <th class="px-4 text-xl">Téléphone</th>
                    <th class="px-4 text-xl">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                        $i=1;
                    @endphp

                    @foreach ($users as $user)
                        @if ($user['id']==auth()->user()->id)

                        @else

                            <tr class="border-t-4 border-white-600">
                                <td class="px-4 text-center text-lg">{{ $i }}</td>
                                <td class="px-4 text-center text-lg">{{$user['name'] }}</td>
                                <td class="px-4 text-center text-lg">{{$user['firstname'] }}</td>
                                <td class="px-4 text-center text-lg">{{$user['email'] }}</td>
                                <td class="px-4 text-center text-lg">{{$user['phone'] }}</td>
                                @if ($user['isBanned']==0)
                                    <td class="px-4 text-center text-lg">
                                        <form action="{{ route('admin.state') }}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" value="{{ $user['id'] }}" name="user_id" />
                                            <x-desactive-button class="my-5">
                                                {{ __('Desactiver') }}
                                            </x-desactive-button>
                                        </form>
                                    </td>
                                @else
                                    <td class="px-4 text-center text-lg">
                                        <form action="{{ route('admin.state') }}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" value="{{ $user['id'] }}" name="user_id" />
                                            <x-active-button class="my-5">
                                                {{ __('Activer') }}
                                            </x-active-button>
                                        </form>
                                    </td>
                                @endif

                            </tr>
                        @endif

                        @php
                            $i++;
                        @endphp

                    @endforeach


                </tbody>
            </table>

            <div class=" align-center">
                {!! $users->links() !!}
            </div>
        </div>
        </div>

</x-app-layout>
