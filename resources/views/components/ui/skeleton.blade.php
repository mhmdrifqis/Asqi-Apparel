@props(['type' => 'card'])

@if($type === 'card')
    <div class="animate-pulse">
        <div class="bg-surface-secondary aspect-[3/4] rounded-xl mb-3"></div>
        <div class="h-2.5 bg-surface-secondary rounded w-1/3 mb-2"></div>
        <div class="h-3.5 bg-surface-secondary rounded w-3/4 mb-3"></div>
        <div class="h-4 bg-surface-secondary rounded w-1/2"></div>
    </div>
@elseif($type === 'text')
    <div class="animate-pulse space-y-2.5">
        <div class="h-2.5 bg-surface-secondary rounded w-full"></div>
        <div class="h-2.5 bg-surface-secondary rounded w-full"></div>
        <div class="h-2.5 bg-surface-secondary rounded w-5/6"></div>
    </div>
@elseif($type === 'image')
    <div class="animate-pulse bg-surface-secondary w-full h-full rounded-xl"></div>
@elseif($type === 'avatar')
    <div class="animate-pulse bg-surface-secondary w-10 h-10 rounded-full"></div>
@endif
