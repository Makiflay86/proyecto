<button wire:click="toggle"
        title="{{ $liked ? 'Quitar de favoritos' : 'Añadir a favoritos' }}"
        class="flex items-center gap-1.5 transition-colors duration-200 group
               {{ $liked
                   ? 'text-red-500 dark:text-red-400'
                   : 'text-gray-400 dark:text-gray-500 hover:text-red-400 dark:hover:text-red-400' }}">
    <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"
         viewBox="0 0 24 24"
         fill="{{ $liked ? 'currentColor' : 'none' }}"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
    @if($count > 0)
        <span class="text-xs font-medium">{{ $count }}</span>
    @endif
</button>
