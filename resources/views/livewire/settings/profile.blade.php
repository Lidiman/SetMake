<div class="max-w-2xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Settings</h1>
        <p class="text-surface-400 mt-1">Manage your profile and account settings</p>
    </div>

    {{-- Profile Information --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-6">Profile Information</h2>

        <form wire:submit="updateProfile" class="space-y-6">
            {{-- Avatar --}}
            <div>
                <label class="label">Profile Picture</label>
                <div class="mt-2 flex flex-col items-center gap-4 p-6 bg-surface-800/30 rounded-xl border-2 border-dashed border-surface-700 hover:border-primary-500/50 transition-colors">
                    <div class="relative group"
                        x-data="{}"
                        @click="$refs.fileInput.click()">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-3xl font-bold text-white overflow-hidden ring-4 ring-surface-800 cursor-pointer">
                            @if (auth()->user()->hasAvatar())
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <input type="file" accept="image/*" class="hidden" x-ref="fileInput"
                        @change="$dispatch('avatar-file-selected', { file: $event.target.files[0] }); $event.target.value = '';">

                    {{-- Progress bar --}}
                    <div x-data="{ uploading: false, progress: 0 }"
                         x-on:avatar-upload-start.window="uploading = true; progress = 0"
                         x-on:avatar-upload-progress.window="progress = $event.detail.progress"
                         x-on:avatar-upload-finish.window="uploading = false"
                         x-on:avatar-upload-error.window="uploading = false"
                         x-show="uploading"
                         x-cloak
                         class="w-full max-w-xs">
                        <div class="flex items-center gap-3 text-sm text-surface-400 mb-1">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span>Uploading... <span x-text="progress + '%'"></span></span>
                        </div>
                        <div class="w-full h-2 bg-surface-800 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-500 rounded-full transition-all duration-300 ease-out"
                                 :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-surface-300">Click the avatar to upload a new photo</p>
                        <p class="text-xs text-surface-500 mt-1">JPG, PNG, WEBP. Max 2MB.</p>
                    </div>
                    @if ($avatar)
                        <div class="flex items-center gap-2 text-sm text-primary-400 bg-primary-500/10 px-3 py-1.5 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            New photo ready
                        </div>
                    @endif
                    @error('avatar') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
                    @if (auth()->user()->hasAvatar())
                        <button type="button" wire:click="removeAvatar" wire:confirm="Remove your profile picture?" class="text-sm text-red-400 hover:text-red-300 transition-colors">
                            Remove current photo
                        </button>
                    @endif
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="label">Name</label>
                <input wire:model="name" type="text" id="name" class="input" placeholder="Your name">
                @error('name') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="label">Username</label>
                <input wire:model="username" type="text" id="username" class="input" placeholder="yourusername">
                @error('username') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-6">Change Password</h2>

        <form wire:submit="updatePassword" class="space-y-5">
            <div>
                <label for="current_password" class="label">Current Password</label>
                <input wire:model="current_password" type="password" id="current_password" class="input" placeholder="••••••••">
                @error('current_password') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="new_password" class="label">New Password</label>
                <input wire:model="new_password" type="password" id="new_password" class="input" placeholder="••••••••">
                @error('new_password') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="new_password_confirmation" class="label">Confirm New Password</label>
                <input wire:model="new_password_confirmation" type="password" id="new_password_confirmation" class="input" placeholder="••••••••">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Change Password</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="card border border-red-500/20">
        <h2 class="text-lg font-semibold text-red-400 mb-6">Danger Zone</h2>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-200">Sign Out</p>
                    <p class="text-xs text-surface-500 mt-0.5">Sign out of your account on this device</p>
                </div>
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </div>
        </form>
    </div>

    {{-- Crop Modal --}}
    <div x-data="avatarCropper()"
         x-cloak
         x-show="open"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="destroyCropper()"
         @avatar-file-selected.window="handleFile($event.detail.file)">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="destroyCropper()"></div>

        <div class="relative w-full max-w-lg bg-surface-900 rounded-2xl shadow-2xl border border-surface-700 overflow-hidden"
             x-transition:enter="transition-all duration-300 delay-75"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition-all duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.outside="destroyCropper()">
            <div class="flex items-center justify-between px-5 py-4 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Crop Avatar</h3>
                <button @click="destroyCropper()" class="text-surface-500 hover:text-surface-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-1">
                <div class="bg-black/40 rounded-lg overflow-hidden" style="height: 55vh;">
                    <img x-ref="image" src="" alt="Crop preview" style="display: none;">
                    <div class="flex items-center justify-center h-full text-surface-500 text-sm" x-show="loading">
                        <svg class="animate-spin w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Loading image...
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-surface-800">
                <button @click="destroyCropper()" class="btn-ghost text-sm">Cancel</button>
                <button @click="crop()" class="btn-primary text-sm" x-bind:disabled="cropping">
                    <span x-show="!cropping">Save Crop</span>
                    <span x-show="cropping" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function avatarCropper() {
        return {
            open: false,
            loading: false,
            cropping: false,
            cropper: null,
            file: null,

            handleFile(file) {
                if (!file) return;

                if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: 'Invalid format. Allowed: JPG, PNG, WEBP.', type: 'error' }
                    }));
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: 'File too large. Maximum 2MB.', type: 'error' }
                    }));
                    return;
                }

                this.file = file;
                this.open = true;
                this.loading = true;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.$nextTick(() => {
                        const img = this.$refs.image;
                        img.src = e.target.result;
                        img.style.display = 'block';
                        this.loading = false;

                        this.$nextTick(() => {
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new Cropper(img, {
                                aspectRatio: 1,
                                viewMode: 3,
                                autoCropArea: 1,
                                responsive: true,
                                movable: true,
                                zoomable: true,
                                rotatable: false,
                                scalable: false,
                                background: false,
                                guides: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                minCropBoxWidth: 64,
                                minCropBoxHeight: 64,
                            });
                        });
                    });
                };
                reader.readAsDataURL(file);
            },

            crop() {
                if (!this.cropper) return;

                this.cropping = true;

                const canvas = this.cropper.getCroppedCanvas({
                    width: 512,
                    height: 512,
                });

                canvas.toBlob((blob) => {
                    const croppedFile = new File([blob], 'avatar.webp', { type: 'image/webp' });

                    window.dispatchEvent(new CustomEvent('avatar-upload-start'));

                    @this.upload('avatar', croppedFile, () => {
                        window.dispatchEvent(new CustomEvent('avatar-upload-finish'));
                        this.destroyCropper();
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { message: 'Photo uploaded. Save changes to apply.', type: 'success' }
                        }));
                    }, (error) => {
                        window.dispatchEvent(new CustomEvent('avatar-upload-error'));
                        console.error('Upload failed:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { message: 'Upload failed. Please try again.', type: 'error' }
                        }));
                    }, (progress) => {
                        window.dispatchEvent(new CustomEvent('avatar-upload-progress', {
                            detail: { progress: Math.round(progress.detail?.progress ?? progress) }
                        }));
                    });
                }, 'image/webp', 0.9);
            },

            destroyCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                this.open = false;
                this.loading = false;
                this.cropping = false;
                this.file = null;
                this.$refs.image.src = '';
                this.$refs.image.style.display = 'none';
            },
        };
    }
</script>
@endpush
