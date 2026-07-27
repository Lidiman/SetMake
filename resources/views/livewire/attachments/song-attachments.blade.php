<div class="card space-y-4">
    <div class="flex items-center justify-between border-b border-surface-800 pb-3">
        <h2 class="text-lg font-semibold text-white">Attachments</h2>
        <button wire:click="$toggle('showUploadForm')" class="btn-ghost btn-sm text-primary-400 hover:text-primary-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload
        </button>
    </div>

    @if($showUploadForm)
        <form wire:submit="upload" class="p-4 rounded-xl bg-surface-800/30 border border-surface-700 space-y-4">
            <div>
                <label class="label">File *</label>
                <input type="file" wire:model="file" class="input file:bg-surface-700 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-2 file:mr-3 file:cursor-pointer">
                @error('file') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                <div wire:loading wire:target="file" class="text-primary-400 text-sm mt-1">Uploading...</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Name *</label>
                    <input type="text" wire:model="name" class="input" placeholder="e.g. Lead Sheet">
                    @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="label">Type</label>
                    <select wire:model="type" class="input">
                        <option value="pdf">PDF</option>
                        <option value="docx">DOCX</option>
                        <option value="txt">Text</option>
                        <option value="image">Image</option>
                        <option value="mp3">MP3 Audio</option>
                        <option value="wav">WAV Audio</option>
                        <option value="mp4">MP4 Video</option>
                        <option value="backing_track">Backing Track</option>
                        <option value="lyrics">Lyrics</option>
                        <option value="chord_sheet">Chord Sheet</option>
                        <option value="stage_notes">Stage Notes</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" wire:click="$set('showUploadForm', false)" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary btn-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>Upload</span>
                    <span wire:loading>Uploading...</span>
                </button>
            </div>
        </form>
    @endif

    @if($attachments->count() > 0)
        <div class="space-y-2">
            @foreach($attachments as $attachment)
                <div class="flex items-center gap-4 p-3 rounded-xl bg-surface-800/30 hover:bg-surface-800/50 border border-surface-800 transition-colors group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-surface-700">
                        <svg class="w-5 h-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $attachment->name }}</p>
                        <p class="text-xs text-surface-400">{{ $attachment->type }} · {{ $attachment->uploader?->name ?? 'Unknown' }} · {{ $attachment->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}" target="_blank" class="btn-ghost btn-sm" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <button wire:click="delete({{ $attachment->id }})" wire:confirm="Delete this attachment?" class="btn-ghost btn-sm text-red-400 hover:text-red-300" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-surface-500 text-sm text-center py-4">No attachments yet. Upload chord sheets, lyrics, or backing tracks.</p>
    @endif
</div>
