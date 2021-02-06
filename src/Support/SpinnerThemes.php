<?php

namespace Savks\Consoler\Support;

class SpinnerThemes
{
    public const DEFAULT = [
        'start' => '<fg=white>⌚</>',
        'progress' => [
            '<fg=cyan>⠏</>',
            '<fg=cyan>⠛</>',
            '<fg=cyan>⠹</>',
            '<fg=cyan>⢸</>',
            '<fg=cyan>⣰</>',
            '<fg=cyan>⣤</>',
            '<fg=cyan>⣆</>',
            '<fg=cyan>⡇</>',
        ],
        'success' => '<fg=green>✔</>',
        'fail' => '<fg=red>✘</>',
    ];
}
