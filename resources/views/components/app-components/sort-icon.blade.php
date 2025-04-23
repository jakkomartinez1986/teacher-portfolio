@if ($sortField !== $field)
    <flux:icon name="arrow-path-rounded-square" variant="micro" class="text-amber-500 dark:text-amber-300" />
@elseif ($sortDirection)
    <flux:icon name="chevron-up" variant="micro" class="text-amber-500 dark:text-amber-300" />
@else
    <flux:icon name="chevron-down" variant="micro" class="text-amber-500 dark:text-amber-300" />
@endif
