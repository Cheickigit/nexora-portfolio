@props([
    'columns' => 40, 
    'chars' => 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789<>[]{}',
    'color' => '#00d4ff' // Couleur Cyan par défaut
])

<div class="matrix-bg" style="--matrix-color: {{ $color }};">
    @for ($i = 0; $i < $columns; $i++)
        @php
            // Génère un texte aléatoire entre 30 et 60 caractères
            $text = '';
            $length = rand(30, 60);
            for ($j = 0; $j < $length; $j++) {
                $text .= mb_substr($chars, rand(0, mb_strlen($chars) - 1), 1);
            }
            
            // Calcule les délais et vitesses aléatoires
            $delay = rand(0, 800) / 100; // 0s à 8s
            $duration = rand(500, 1200) / 100; // 5s à 12s
            
            // Position horizontale en pourcentage (pour s'adapter à tous les écrans)
            $left = ($i * (100 / $columns)) . '%';
        @endphp

        <div class="matrix-column" style="
            left: {{ $left }}; 
            animation-delay: -{{ $delay }}s; 
            animation-duration: {{ $duration }}s;
            font-size: {{ rand(12, 18) }}px;
        ">
            {{ $text }}
        </div>
    @endfor
</div>