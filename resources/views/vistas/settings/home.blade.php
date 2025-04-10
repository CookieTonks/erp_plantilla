<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Informacion de los roles') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Roles y Permisos') }}
                            </p>
                        </header>
                        <h1>Roles y Permisos</h1>

                        <!-- Crear nuevo rol -->
                        <form method="POST" action="{{ route('roles.store') }}">
                            @csrf
                            <div>
                                <x-input-label for="name" :value="__('Role Name')" />
                                <input type="text" name="name" id="name" placeholder="Role Name" required class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <x-primary-button  type="submit">Crear Rol</x-primary-button>
                        </form>

                        <h2>Asignar Permiso a Rol</h2>
                        <form method="POST" action="{{ route('roles.assign-permission') }}">
                            @csrf
                            <div>
                                <x-input-label for="role_name" :value="__('Seleccionar Rol')" />
                                <select name="role_name" id="role_name" class="mt-1 block w-full" required>
                                    <option value="" disabled selected>Seleccionar Rol</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role_name')" />
                            </div>

                            <div>
                                <x-input-label for="permission_name" :value="__('Seleccionar Permiso')" />
                                <select name="permission_name" id="permission_name" class="mt-1 block w-full" required>
                                    <option value="" disabled selected>Seleccionar Permiso</option>
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('permission_name')" />
                            </div>

                            <x-primary-button type="submit">Asignar Permiso</x-primary-button>
                        </form>

                        <h2>Asignar Rol a un usuario</h2>
                        <form method="POST" action="{{ route('roles.assign') }}">
                            @csrf
                            <div>
                                <x-input-label for="user_id" :value="__('Seleccionar Usuario')" />
                                <select name="user_id" id="user_id" class="mt-1 block w-full" required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="role_name" :value="__('Seleccionar Rol')" />
                                <select name="role_name" id="role_name" class="mt-1 block w-full" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role_name')" />
                            </div>

                            <x-primary-button type="submit">Asignar Rol</x-primary-button>
                        </form>

                        <h2>Quitar Rol a Usuario</h2>
                        <form method="POST" action="{{ route('roles.remove') }}">
                            @csrf
                            <div>
                                <x-input-label for="user_id" :value="__('Seleccionar Usuario')" />
                                <select name="user_id" id="user_id" class="mt-1 block w-full" required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="role_name" :value="__('Seleccionar Rol')" />
                                <select name="role_name" id="role_name" class="mt-1 block w-full" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role_name')" />
                            </div>

                            <x-primary-button type="submit">Quitar Rol</x-primary-button>
                        </form>

                        <h2>Quitar Permiso de Rol</h2>
                        <form method="POST" action="{{ route('permissions.remove') }}">
                            @csrf
                            <div>
                                <x-input-label for="role_name" :value="__('Seleccionar Rol')" />
                                <select name="role_name" id="role_name" class="mt-1 block w-full" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role_name')" />
                            </div>

                            <div>
                                <x-input-label for="permission_name" :value="__('Seleccionar Permiso')" />
                                <select name="permission_name" id="permission_name" class="mt-1 block w-full" required>
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('permission_name')" />
                            </div>

                            <x-primary-button type="submit">Quitar Permiso</x-primary-button>
                        </form>

                        <h2>Crear Permiso</h2>
                        <form method="POST" action="{{ route('permissions.create') }}">
                            @csrf
                            <div>
                                <x-input-label for="permission_name" :value="__('Nombre del Permiso')" />
                                <input type="text" name="permission_name" id="permission_name" placeholder="Nombre del Permiso" required class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('permission_name')" />
                            </div>
                            <x-primary-button type="submit">Crear Permiso</x-primary-button>
                        </form>

                        <h2>Asignar Permiso a Usuario</h2>
                        <form method="POST" action="{{ route('permissions.give') }}">
                            @csrf
                            <div>
                                <x-input-label for="user_id" :value="__('Seleccionar Usuario')" />
                                <select name="user_id" id="user_id" class="mt-1 block w-full" required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="permission_name" :value="__('Seleccionar Permiso')" />
                                <select name="permission_name" id="permission_name" class="mt-1 block w-full" required>
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('permission_name')" />
                            </div>

                            <x-primary-button type="submit">Asignar Permiso</x-primary-button>
                        </form>

                        <h2>Revocar Permiso de Usuario</h2>
                        <form method="POST" action="{{ route('permissions.revoke') }}">
                            @csrf
                            <div>
                                <x-input-label for="user_id" :value="__('Seleccionar Usuario')" />
                                <select name="user_id" id="user_id" class="mt-1 block w-full" required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="permission_name" :value="__('Seleccionar Permiso')" />
                                <select name="permission_name" id="permission_name" class="mt-1 block w-full" required>
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('permission_name')" />
                            </div>

                            <x-primary-button type="submit">Revocar Permiso</x-primary-button>
                        </form>



                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>