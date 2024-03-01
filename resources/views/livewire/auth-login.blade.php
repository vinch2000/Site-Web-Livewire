<div>

        <div class="container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">{{ config('app.name') }}</div>
            
                            <div class="card-body">
                                <form wire:submit.prevent="save"> 
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label">Email</label>
            
                                        <div class="col-md-8">
                                            <input id="email" type="email" class="form-control" wire:model="email">
                                            @error('email') <span class="error">{{ $message }}</span> @enderror 
                                        </div>
                                    </div>
            
                                    <div class="row mb-4">
                                        <label for="password" class="col-md-4 col-form-label">Password</label>
            
                                        <div class="col-md-8">
                                            <input id="password" type="password" class="form-control" wire:model="password">
                                            @error('password') <span class="error">{{ $message }}</span> @enderror 
                                        </div>
                                    </div>
            
                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <a href="{{ route("inscription") }}" class="btn btn-secondary w-100">
                                                Créer un nouveau compte
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary w-100" >
                                                Connexion
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
