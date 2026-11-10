<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['header' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['header' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin — NEXORA</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/nexora-mark.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="admin-body antialiased text-slate-100">
    <div id="scroll-progress"></div>

    <nav class="sticky top-0 z-40 backdrop-blur bg-[#04050d]/80 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-y-2 justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2">
                    <img src="<?php echo e(asset('images/nexora-mark.png')); ?>" alt="NEXORA" class="h-8 w-auto">
                    <span class="font-bold text-lg">Admin</span>
                </a>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-sm hover:text-cyan-300 transition <?php echo e(request()->routeIs('admin.dashboard') ? 'text-cyan-300' : 'text-slate-300'); ?>">Dashboard</a>
                <a href="<?php echo e(route('admin.projects.index')); ?>" class="text-sm hover:text-cyan-300 transition <?php echo e(request()->routeIs('admin.projects.*') ? 'text-cyan-300' : 'text-slate-300'); ?>"><?php echo e(isset($currentPortfolio) ? ($currentPortfolio->profileConfig()['projects_label']['fr'] ?? 'Projets') : 'Projets'); ?></a>
                <a href="<?php echo e(route('admin.profile')); ?>" class="text-sm hover:text-cyan-300 transition <?php echo e(request()->routeIs('admin.profile') ? 'text-cyan-300' : 'text-slate-300'); ?>">Profil</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['experiences' => 'Expériences', 'educations' => 'Formations', 'skills' => 'Compétences', 'languages' => 'Langues', 'certifications' => 'Certifications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e(route('admin.sections', $key)); ?>" class="text-sm hover:text-cyan-300 transition <?php echo e(request()->route('section') === $key ? 'text-cyan-300' : 'text-slate-300'); ?>"><?php echo e($text); ?></a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div class="flex gap-4 items-center text-sm">
                <a href="<?php echo e(route('home', ['locale' => 'fr'])); ?>" target="_blank" class="hover:text-cyan-300 transition">Voir le site →</a>
                <span class="text-slate-500 hidden sm:inline"><?php echo e(auth()->user()->name ?? ''); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-slate-400 hover:text-white">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($header): ?>
        <header class="border-b border-white/10">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8"><?php echo e($header); ?></div>
        </header>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <main class="py-8"><?php echo e($slot); ?></main>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scriptConfig(); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\portfolio\resources\views/components/admin-layout.blade.php ENDPATH**/ ?>