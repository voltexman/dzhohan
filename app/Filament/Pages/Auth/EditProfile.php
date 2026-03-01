<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    protected function getRedirectUrl(): string
    {
        return filament()->getPanel('admin')->getUrl();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar_url')
                    ->hiddenLabel()
                    ->label('Ваша аватарка')
                    ->disk('public')
                    ->avatar()
                    ->imageEditor()
                    ->directory('avatars')
                    ->visibility('public'),

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
