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
                    {{ __("Smart Don a en tout $total_user utilisateurs!") }}
                </div>
            </div>
        </div>


        <div class="flex flex-col justify-center py-7 lg:mx-12">
            <table class="table-fixed dark:text-white lg:my-5 space-x-24 ">
                <thead class="">
                  <tr>

                    <th class="px-4 text-md">Nom</th>
                    <th class="px-4 text-md">Prénom</th>
                    <th class="px-4 text-md">Email</th>
                    <th class="px-4 text-md">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                        $i=1;
                    @endphp

                    @foreach ($users as $user)


                            <tr class="border-t-4 border-white-600">

                                <td class="px-4 text-center text-sm">{{$user['name'] }}</td>
                                <td class="px-4 text-center text-sm">{{$user['firstname'] }}</td>
                                <td class="px-4 text-center text-sm">{{$user['email'] }}</td>
                                @if ($user['isAdmin']==1)
                                    <td class="px-4 text-center text-sm">Admin</td>

                                @else
                                    @if ($user['isBanned']==0)
                                        <td class="px-4 text-center text-lg">
                                            <form action="{{ route('admin.state') }}" method="post">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" value="{{ $user['id'] }}" name="user_id" />
                                                <x-desactive-button class="lg:my-5">
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
                                @endif

                            </tr>
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
