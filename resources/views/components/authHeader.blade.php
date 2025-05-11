<x-layout>
    <header>
        <nav>
            @guest
                <a href="{{ route('show.login') }}" class="btn">Login</a>
                <a href="{{ route('show.register') }}" class="btn">Register</a>
            @endguest

            @auth
                <span class="border-r-2 pr-2">
                    Hi there, {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }} " method="POST" class="m-0">
                    @csrf
                    <button class="btn">Logout</button>
                </form>
            @endauth
        </nav>
    </header>

    <main class="container">
        {{ $slot }}
    </main>
</x-layout>
