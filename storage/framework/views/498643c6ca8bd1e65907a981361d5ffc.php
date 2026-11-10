<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-bold text-2xl text-gradient">Tableau de bord — Portfolio</h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6 py-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $portfolio): ?>
            <div class="panel p-6 border-amber-500/40 text-amber-300">
                Aucun portfolio n'est associé à votre compte. Contactez l'administrateur.
            </div>
        <?php else: ?>
            <?php
                $projectsLabel = $portfolio->profileConfig()['projects_label']['fr'] ?? 'Projets';
            ?>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                    [$projectsLabel, $portfolio->projects_count],
                    ['Compétences', $portfolio->skills_count],
                    ['Expériences', $portfolio->experiences_count],
                    ['Formations', $portfolio->educations_count],
                    ['Langues', $portfolio->languages_count],
                    ['Certifications', $portfolio->certifications_count],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $n]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="glass p-6">
                        <div class="text-sm text-slate-400"><?php echo e($label); ?></div>
                        <div class="text-4xl font-bold text-gradient mt-1"><span data-count="<?php echo e($n); ?>">0</span></div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <?php
                $steps = [
                    ['Compléter mon profil (nom, résumé, contact)', filled($portfolio->full_name) && filled($portfolio->summary_fr), route('admin.profile')],
                    ['Ajouter une formation', $portfolio->educations_count > 0, route('admin.sections', 'educations')],
                    ['Ajouter une expérience', $portfolio->experiences_count > 0, route('admin.sections', 'experiences')],
                    ['Ajouter mes compétences', $portfolio->skills_count > 0, route('admin.sections', 'skills')],
                    ['Ajouter : ' . $projectsLabel, $portfolio->projects_count > 0, route('admin.projects.index')],
                    ['Publier mon portfolio', (bool) $portfolio->is_public, route('admin.profile')],
                ];
                $done = collect($steps)->filter(fn ($s) => $s[1])->count();
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($done < count($steps)): ?>
                <div class="panel p-6 space-y-3">
                    <h3 class="font-semibold text-lg">Guide de démarrage — <?php echo e($done); ?>/<?php echo e(count($steps)); ?></h3>
                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-2 bg-cyan-400" style="width: <?php echo e(round($done / count($steps) * 100)); ?>%"></div>
                    </div>
                    <ul class="space-y-2 pt-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$text, $ok, $url]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li class="flex items-center gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ok): ?>
                                    <span class="text-cyan-300">&#10003;</span>
                                    <span class="text-slate-500 line-through"><?php echo e($text); ?></span>
                                <?php else: ?>
                                    <span class="text-slate-500">&#9675;</span>
                                    <a href="<?php echo e($url); ?>" class="text-cyan-300 hover:underline"><?php echo e($text); ?></a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="panel p-6 space-y-4">
                <h3 class="font-semibold text-lg">Actions rapides</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="btn-primary !py-2">Éditer mon profil</a>
                    <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn-soft">Gérer : <?php echo e($projectsLabel); ?></a>
                    <a href="<?php echo e(route('admin.sections', 'experiences')); ?>" class="btn-soft">Gérer mes expériences</a>
                    <a href="<?php echo e(route('admin.sections', 'educations')); ?>" class="btn-soft">Gérer mes formations</a>
                    <a href="<?php echo e(route('admin.sections', 'skills')); ?>" class="btn-soft">Gérer mes compétences</a>
                </div>
            </div>

            <div class="panel p-6 space-y-3">
                <h3 class="font-semibold text-lg">Mon CV</h3>
                <p class="text-sm text-slate-400">Généré à partir de votre profil. Seuls les éléments cochés « Inclure dans le CV » apparaissent.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('admin.cv', 'fr')); ?>" target="_blank" class="btn-primary !py-2">Aperçu FR</a>
                    <a href="<?php echo e(route('admin.cv', 'en')); ?>" target="_blank" class="btn-primary !py-2">Aperçu EN</a>
                    <a href="<?php echo e(route('admin.cv', ['locale' => 'fr', 'download' => 1])); ?>" class="btn-soft">Télécharger FR</a>
                    <a href="<?php echo e(route('admin.cv', ['locale' => 'en', 'download' => 1])); ?>" class="btn-soft">Télécharger EN</a>
                </div>
            </div>

            
            <div class="panel p-6">
                <a href="<?php echo e(route('home', ['locale' => 'fr'])); ?>" target="_blank" class="text-cyan-300 hover:underline">→ Voir mon portfolio public</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\portfolio\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>