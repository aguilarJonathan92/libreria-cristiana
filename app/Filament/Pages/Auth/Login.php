<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public static function getSimplePageMaxContentWidth(): Width | string | null
    {
        return Width::Small;
    }

    public function getHeading(): string | Htmlable
    {
        return new HtmlString('
            <div style="text-align: center; margin-bottom: 4px;">
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#15803d;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
            </div>
        ');
    }

    public function getSubheading(): string | Htmlable | null
    {
        return new HtmlString(
            '<span style="font-size:13px;color:#6b7280;">Ingresá tus credenciales para continuar</span>'
        );
    }
}