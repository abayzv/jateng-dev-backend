<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Tag;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->after(fn () => $this->record->tags()->delete()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $existingTags = $this->record->tags()->pluck('tags')->toArray();
        $tags = explode(',', $data['tags']);
        $deletedTags = array_diff($existingTags, $tags);

        foreach ($tags as $tag) {
            $this->record->tags()->updateOrCreate([
                'tags' => $tag,
            ]);
        }

        foreach ($deletedTags as $deletedTag) {
            $this->record->tags()->where('tags', $deletedTag)->delete();
        }

        $data['author_id'] = auth()->user()->id;
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tags'] = $this->record->tags()->pluck('tags')->toArray();
        return $data;
    }
}
