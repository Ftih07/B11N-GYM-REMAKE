<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - B1NG Empire</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        header: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        dark: '#0a0a0a',
                        card: '#171717',
                        primary: '#DC2626',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark font-body flex items-center justify-center h-screen px-4 bg-[url('/img/gym-bg-pattern.png')]">
    <div class="bg-card p-10 rounded-xl shadow-2xl w-full max-w-md border border-neutral-800 relative overflow-hidden group">

        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-red-500 to-orange-500"></div>

        <div class="text-center mb-10">
            <h1 class="text-4xl font-header font-bold text-white tracking-wider">
                B1NG <span class="text-primary">EMPIRE</span>
            </h1>
            <p class="text-gray-500 text-sm mt-2 uppercase tracking-widest font-bold">Member Area Access</p>
        </div>

        <a href="{{ route('auth.google') }}"
            class="group flex items-center justify-center w-full bg-white hover:bg-gray-200 text-gray-900 font-bold py-4 px-6 rounded transition duration-300 transform hover:-translate-y-1 shadow-lg">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6 mr-4 group-hover:scale-110 transition">
            <span class="font-body">Masuk dengan Google</span>
        </a>

        <div class="mt-8 pt-8 border-t border-neutral-800 text-center">
            <p class="text-gray-600 text-xs leading-relaxed">
                Belum terdaftar sebagai member?<br>
                Silahkan hubungi admin di <span class="text-primary font-bold">Front Office</span>.
            </p>
        </div>
    </div>

</body>

</html>